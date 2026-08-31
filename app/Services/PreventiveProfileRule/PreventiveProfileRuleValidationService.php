<?php

declare(strict_types=1);

namespace App\Services\PreventiveProfileRule;

use App\Models\OperationalUnit;
use App\Enums\PreventiveProfileRuleType;
use App\Models\PreventiveProfile;
use App\Models\PreventiveProfileRule;
use App\Models\Activity;
use Illuminate\Validation\ValidationException;


/**
 * Validações de regras de perfil preventivo.
 */
class PreventiveProfileRuleValidationService
{
    /**
     * Garante que uma regra pertence ao perfil informado.
     */
    public function validateRuleBelongsToProfile(
        PreventiveProfile $profile,
        PreventiveProfileRule $rule
    ): void {
        $belongsToProfile = $rule
            ->preventiveProfileBranch()
            ->where(
                'preventive_profile_id',
                $profile->id
            )
            ->exists();

        if (!$belongsToProfile) {
            throw ValidationException::withMessages([
                'rule' =>
                'A regra não pertence ao perfil de preventiva informado.',
            ]);
        }
    }

    /**
     * Garante que a filial pertence ao perfil.
     */
    public function validateBranchBelongsToProfile(
        PreventiveProfile $profile,
        int $preventiveProfileBranchId
    ): void {
        $exists = $profile
            ->branches()
            ->whereKey($preventiveProfileBranchId)
            ->exists();

        if (!$exists) {
            throw ValidationException::withMessages([
                'preventive_profile_branch_id' =>
                'A filial selecionada não pertence ao perfil de preventiva.',
            ]);
        }
    }

    /**
     * Valida a composição de uma regra específica.
     *
     * Regras:
     *
     * 1. Deve possuir pelo menos uma unidade.
     * 2. Deve possuir pelo menos uma atividade.
     * 3. Não pode possuir exatamente as mesmas atividades
     *    da regra ALL da mesma filial.
     * 4. Todas as unidades precisam pertencer à filial.
     * 5. Uma unidade não pode estar vinculada a outra
     *    regra SPECIFIC da mesma filial.
     *
     * @param array<int, mixed> $unitIds
     * @param array<int, mixed> $activityIds
     */
    public function validateSpecificRule(
        PreventiveProfileRule $rule,
        array $unitIds,
        array $activityIds
    ): void {
        $unitIds = array_values(
            array_unique(
                array_map('intval', $unitIds)
            )
        );

        $activityIds = array_values(
            array_unique(
                array_map('intval', $activityIds)
            )
        );

        /*
         * Uma regra específica precisa possuir
         * pelo menos uma unidade.
         */
        if (empty($unitIds)) {
            throw ValidationException::withMessages([
                'operational_unit_ids' =>
                'Uma regra específica deve possuir pelo menos uma unidade operacional.',
            ]);
        }

        /*
         * Toda regra precisa possuir atividades.
         */
        if (empty($activityIds)) {
            throw ValidationException::withMessages([
                'activity_ids' =>
                'A regra deve possuir pelo menos uma atividade.',
            ]);
        }

        /*
         * Garante que todas as unidades selecionadas
         * pertencem à filial da regra.
         */
        $branchId = $rule
            ->preventiveProfileBranch()
            ->value('branch_id');

        if (!$branchId) {
            throw ValidationException::withMessages([
                'preventive_profile_branch_id' =>
                'Não foi possível identificar a filial da regra.',
            ]);
        }

        $validUnitIds = OperationalUnit::query()
            ->whereIn('id', $unitIds)
            ->where('branch_id', $branchId)
            ->pluck('id')
            ->map(fn($id) => (int) $id)
            ->all();

        $invalidUnitIds = array_values(
            array_diff(
                $unitIds,
                $validUnitIds
            )
        );

        if (!empty($invalidUnitIds)) {
            throw ValidationException::withMessages([
                'operational_unit_ids' =>
                'Uma ou mais unidades selecionadas não pertencem à filial da regra.',
            ]);
        }

        /*
         * Procura a regra ALL da mesma filial.
         */
        $baseRule = PreventiveProfileRule::query()
            ->where(
                'preventive_profile_branch_id',
                $rule->preventive_profile_branch_id
            )
            ->where('rule_type', 'all')
            ->with('activities')
            ->first();

        /*
         * Se existir uma ALL, a SPECIFIC não pode possuir
         * exatamente a mesma composição.
         *
         * Importante:
         *
         * ALL = A, B, C
         *
         * SPECIFIC = A, C
         * -> válido
         *
         * SPECIFIC = A, B
         * -> válido
         *
         * SPECIFIC = A, B, C
         * -> inválido
         */
        if ($baseRule) {
            $baseActivityIds = $baseRule->activities
                ->pluck('activity_id')
                ->map(fn($id) => (int) $id)
                ->sort()
                ->values()
                ->all();

            $specificActivityIds = collect($activityIds)
                ->map(fn($id) => (int) $id)
                ->sort()
                ->values()
                ->all();

            if ($baseActivityIds === $specificActivityIds) {
                throw ValidationException::withMessages([
                    'activity_ids' =>
                    'A regra específica deve possuir uma composição de atividades diferente da regra ALL.',
                ]);
            }
        }

        /*
         * Verifica se alguma unidade já está vinculada
         * a outra regra SPECIFIC da mesma filial.
         *
         * Durante uma edição, a própria regra é ignorada.
         */
        $alreadyUsed = PreventiveProfileRule::query()
            ->where(
                'preventive_profile_branch_id',
                $rule->preventive_profile_branch_id
            )
            ->where('rule_type', 'specific')
            ->when(
                $rule->exists,
                fn($query) => $query->whereKeyNot($rule->id)
            )
            ->whereHas(
                'units',
                function ($query) use ($unitIds) {
                    $query->whereIn(
                        'operational_unit_id',
                        $unitIds
                    );
                }
            )
            ->with('units')
            ->get()
            ->flatMap(
                fn($specificRule) =>
                $specificRule->units
                    ->pluck('operational_unit_id')
            )
            ->map(fn($id) => (int) $id)
            ->unique()
            ->intersect($unitIds)
            ->values()
            ->all();

        if (!empty($alreadyUsed)) {
            throw ValidationException::withMessages([
                'operational_unit_ids' =>
                'Uma ou mais unidades selecionadas já possuem uma regra específica configurada para esta filial.',
            ]);
        }
    }
    public function validateRuleType(
        PreventiveProfileRule $rule,
        PreventiveProfileRuleType $expectedType
    ): void {
        if ($rule->rule_type !== $expectedType) {
            throw new \DomainException(
                "A regra informada não é uma regra do tipo {$expectedType->value}."
            );
        }
    }

    public function validateActivitiesForProfile(
        PreventiveProfile $profile,
        array $activityIds
    ): void {
        $activityIds = collect($activityIds)
            ->map(fn($id) => (int) $id)
            ->unique()
            ->values();

        if ($activityIds->isEmpty()) {
            return;
        }

        $validCount = Activity::query()
            ->whereIn('id', $activityIds)
            ->where(
                'preventive_type_id',
                $profile->preventive_type_id
            )
            ->where('active', true)
            ->count();

        if ($validCount !== $activityIds->count()) {
            throw ValidationException::withMessages([
                'activity_ids' =>
                'Uma ou mais atividades selecionadas não são compatíveis com o tipo desta preventiva.',
            ]);
        }
    }
}

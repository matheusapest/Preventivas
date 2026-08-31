<?php

declare(strict_types=1);

namespace App\Services\Preventive\Configuration;

use App\Models\Activity;
use App\Models\OperationalUnit;
use App\Models\Configuration\Preventive\PreventiveProfile;
use App\Models\Configuration\Preventive\PreventiveProfileBranch;
use App\Models\Configuration\Preventive\PreventiveProfileRule;
use App\Enums\PreventiveProfileRuleType;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PreventiveProfileSpecificRuleService
{
    /**
     * Retorna as unidades operacionais ativas da filial
     * que ainda não possuem uma regra específica.
     *
     * @return Collection<int, OperationalUnit>
     */
    public function getAvailableOperationalUnits(
        PreventiveProfileBranch $profileBranch
    ): Collection {
        $specificRules = PreventiveProfileRule::query()
            ->where(
                'preventive_profile_branch_id',
                $profileBranch->id
            )
            ->where(
                'rule_type',
                PreventiveProfileRuleType::SPECIFIC->value
            )
            ->with('units')
            ->get();

        $usedOperationalUnitIds = $specificRules
            ->flatMap(
                fn ($specificRule) =>
                $specificRule->units->pluck('operational_unit_id')
            )
            ->unique()
            ->values();

        return OperationalUnit::query()
            ->active()
            ->where(
                'branch_id',
                $profileBranch->branch_id
            )
            ->when(
                $usedOperationalUnitIds->isNotEmpty(),
                fn ($query) =>
                $query->whereNotIn(
                    'id',
                    $usedOperationalUnitIds
                )
            )
            ->orderBy('identifier')
            ->get();
    }

    /**
     * Cria uma regra específica para uma unidade operacional.
     *
     * A regra específica pertence à mesma filial da regra ALL
     * informada e sobrescreve a regra padrão para a unidade.
     *
     * @param array<string, mixed> $data
     */
    public function create(
        PreventiveProfile $profile,
        PreventiveProfileRule $rule,
        array $data
    ): PreventiveProfileRule {
        $this->validateRuleBelongsToProfile(
            $profile,
            $rule
        );

        $rule->load([
            'preventiveProfileBranch',
            'activities',
        ]);

        if (
            $rule->rule_type->value !==
            PreventiveProfileRuleType::ALL->value
        ) {
            throw new \DomainException(
                'A regra informada não é uma regra Todos.'
            );
        }

        $profileBranch = $rule->preventiveProfileBranch;

        if (!$profileBranch) {
            throw new \DomainException(
                'A regra não está vinculada a uma filial do perfil.'
            );
        }

        $operationalUnit = OperationalUnit::query()
            ->active()
            ->whereKey($data['operational_unit_id'])
            ->where(
                'branch_id',
                $profileBranch->branch_id
            )
            ->first();

        if (!$operationalUnit) {
            throw new \DomainException(
                'A unidade operacional selecionada não pertence à filial.'
            );
        }

        $activityIds = collect($data['activity_ids'])
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        $validActivityIds = Activity::query()
            ->where('active', true)
            ->whereIn('id', $activityIds)
            ->pluck('id')
            ->map(fn ($id) => (int) $id);

        if (
            $validActivityIds->count() !==
            $activityIds->count()
        ) {
            throw new \DomainException(
                'Uma ou mais atividades selecionadas são inválidas ou estão inativas.'
            );
        }

        $existingRule = PreventiveProfileRule::query()
            ->where(
                'preventive_profile_branch_id',
                $profileBranch->id
            )
            ->where(
                'rule_type',
                PreventiveProfileRuleType::SPECIFIC->value
            )
            ->whereHas(
                'units',
                fn ($query) =>
                $query->where(
                    'operational_unit_id',
                    $operationalUnit->id
                )
            )
            ->exists();

        if ($existingRule) {
            throw new \DomainException(
                'Já existe uma regra específica configurada para esta unidade.'
            );
        }

        $specificRule = PreventiveProfileRule::create([
            'preventive_profile_branch_id' => $profileBranch->id,
            'rule_type' => PreventiveProfileRuleType::SPECIFIC->value,
        ]);

        $specificRule->units()->create([
            'operational_unit_id' => $operationalUnit->id,
        ]);

        $specificRule->activities()->createMany(
            $validActivityIds
                ->map(fn ($activityId) => [
                    'activity_id' => $activityId,
                ])
                ->all()
        );

        return $specificRule->load([
            'preventiveProfileBranch.branch',
            'units.operationalUnit',
            'activities.activity',
        ]);
    }

    /**
     * Atualiza a composição de atividades de uma regra específica.
     *
     * A unidade operacional permanece vinculada à regra.
     *
     * @param array<string, mixed> $data
     */
    public function update(
        PreventiveProfile $profile,
        PreventiveProfileRule $specificRule,
        array $data
    ): void {
        DB::transaction(function () use (
            $profile,
            $specificRule,
            $data
        ): void {
            $this->validateRuleBelongsToProfile(
                $profile,
                $specificRule
            );

            if (
                $specificRule->rule_type !==
                PreventiveProfileRuleType::SPECIFIC
            ) {
                throw new \DomainException(
                    'A regra informada não é uma regra específica.'
                );
            }

            $specificRule->load([
                'preventiveProfileBranch',
                'units',
            ]);

            $profileBranch = $specificRule->preventiveProfileBranch;

            if (!$profileBranch) {
                throw new \DomainException(
                    'A regra específica não possui uma filial vinculada.'
                );
            }

            $operationalUnit = $specificRule
                ->units
                ->first();

            if (!$operationalUnit) {
                throw new \DomainException(
                    'A regra específica não possui uma unidade operacional vinculada.'
                );
            }

            $activityIds = collect($data['activity_ids'])
                ->map(fn ($id) => (int) $id)
                ->unique()
                ->values();

            $validActivityIds = Activity::query()
                ->where('active', true)
                ->whereIn('id', $activityIds)
                ->pluck('id')
                ->map(fn ($id) => (int) $id);

            if (
                $validActivityIds->count() !==
                $activityIds->count()
            ) {
                throw new \DomainException(
                    'Uma ou mais atividades selecionadas são inválidas ou estão inativas.'
                );
            }

            /*
             * Uma regra específica pode ter a mesma composição
             * de outra regra específica.
             *
             * O que não pode acontecer é a composição ser
             * exatamente igual à regra Todos da mesma filial.
             */
            $baseRule = PreventiveProfileRule::query()
                ->where(
                    'rule_type',
                    PreventiveProfileRuleType::ALL
                )
                ->where(
                    'preventive_profile_branch_id',
                    $profileBranch->id
                )
                ->with('activities')
                ->first();

            if ($baseRule) {
                $baseActivityIds = $baseRule->activities
                    ->pluck('activity_id')
                    ->map(fn ($id) => (int) $id)
                    ->sort()
                    ->values()
                    ->all();

                $specificActivityIds = $validActivityIds
                    ->sort()
                    ->values()
                    ->all();

                if (
                    $baseActivityIds ===
                    $specificActivityIds
                ) {
                    throw new \DomainException(
                        'A regra específica não pode possuir a mesma composição de atividades da regra Todos.'
                    );
                }
            }

            /*
             * A unidade não é alterada durante a edição.
             *
             * O operational_unit_id recebido pelo formulário é
             * ignorado propositalmente.
             */
            $specificRule->activities()->delete();

            $specificRule->activities()->createMany(
                $validActivityIds
                    ->map(fn ($activityId) => [
                        'activity_id' => $activityId,
                    ])
                    ->all()
            );
        });
    }


    /**
     * Garante que uma regra pertence ao perfil informado.
     */
    private function validateRuleBelongsToProfile(
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
}

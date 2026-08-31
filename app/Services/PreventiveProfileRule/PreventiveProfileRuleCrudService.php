<?php

declare(strict_types=1);

namespace App\Services\PreventiveProfileRule;

use App\Enums\PreventiveProfileRuleType;
use App\Models\PreventiveProfile;
use App\Models\PreventiveProfileBranch;
use App\Models\PreventiveProfileRule;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * Criação, atualização e remoção da regra ALL (e criação genérica
 * de regras a partir do formulário unificado ALL/SPECIFIC).
 */
class PreventiveProfileRuleCrudService
{
    public function __construct(
        private readonly PreventiveProfileRuleValidationService $validationService,
        private readonly PreventiveProfileRuleSyncService $syncService,
    ) {}

    /**
     * Cria uma nova regra para o perfil de preventiva.
     *
     * @param array<string, mixed> $data
     */
    public function create(
        PreventiveProfile $profile,
        array $data
    ): PreventiveProfileRule {
        return DB::transaction(function () use (
            $profile,
            $data
        ): PreventiveProfileRule {

            $preventiveProfileBranchId = (int) (
                $data['preventive_profile_branch_id']
            );

            $ruleType = $data['rule_type'];

            $unitIds = $data['operational_unit_ids'] ?? [];

            $activityIds = $data['activity_ids'] ?? [];

            /**
             * Garante que a filial pertence ao perfil.
             */
            $this->validationService->validateBranchBelongsToProfile(
                $profile,
                $preventiveProfileBranchId
            );

            /**
             * Garante que todas as atividades pertencem
             * ao tipo de preventiva do perfil.
             */
            $this->validationService->validateActivitiesForProfile(
                $profile,
                $activityIds
            );

            /**
             * Uma filial pode possuir somente uma regra ALL.
             */
            if ($ruleType === 'all') {
                $allExists = PreventiveProfileRule::query()
                    ->where(
                        'preventive_profile_branch_id',
                        $preventiveProfileBranchId
                    )
                    ->where('rule_type', 'all')
                    ->exists();

                if ($allExists) {
                    throw ValidationException::withMessages([
                        'preventive_profile_branch_id' =>
                        'Esta filial já possui uma regra ALL configurada.',
                    ]);
                }
            }

            /**
             * Criamos o model em memória para utilizar
             * nas validações antes da persistência.
             */
            $rule = new PreventiveProfileRule();

            $rule->preventive_profile_branch_id =
                $preventiveProfileBranchId;

            $rule->rule_type = $ruleType;

            /**
             * Valida a regra específica antes de salvar.
             */
            if ($ruleType === 'specific') {
                $this->validationService->validateSpecificRule(
                    $rule,
                    $unitIds,
                    $activityIds
                );
            }

            /**
             * Persiste a regra.
             */
            $rule->save();

            /**
             * SPECIFIC possui unidades vinculadas.
             */
            if ($ruleType === 'specific') {
                $this->syncService->syncUnits(
                    $rule,
                    $unitIds
                );
            }

            /**
             * ALL e SPECIFIC podem possuir atividades.
             */
            $this->syncService->syncActivities(
                $rule,
                $activityIds
            );

            return $this->syncService->loadRule($rule);
        });
    }

    /**
     * Atualiza uma regra.
     *
     * @param array<string, mixed> $data
     */
    public function update(
        PreventiveProfileRule $rule,
        array $data
    ): PreventiveProfileRule {
        if (
            $rule->rule_type->value
            !== PreventiveProfileRuleType::ALL->value
        ) {
            throw new \DomainException(
                'Apenas a regra Todos pode ser atualizada por este método.'
            );
        }

        return DB::transaction(function () use ($rule, $data) {

            $rule->load([
                'preventiveProfileBranch.preventiveProfile',
            ]);

            $profileBranch = $rule->preventiveProfileBranch;

            if (!$profileBranch) {
                throw new \DomainException(
                    'A regra Todos não possui uma filial vinculada.'
                );
            }

            $profile = $profileBranch->preventiveProfile;

            if (!$profile) {
                throw new \DomainException(
                    'A filial da regra não possui um perfil de preventiva vinculado.'
                );
            }

            /**
             * Nova composição da regra Todos.
             */
            $newActivityIds = collect(
                $data['activity_ids'] ?? []
            )
                ->map(fn($id) => (int) $id)
                ->unique()
                ->sort()
                ->values()
                ->all();

            /**
             * Garante que todas as atividades pertencem
             * ao tipo de preventiva do perfil.
             */
            $this->validationService->validateActivitiesForProfile(
                $profile,
                $newActivityIds
            );

            /**
             * Verifica conflito com regras específicas.
             */
            $specificRules = PreventiveProfileRule::query()
                ->where(
                    'preventive_profile_branch_id',
                    $profileBranch->id
                )
                ->where(
                    'rule_type',
                    PreventiveProfileRuleType::SPECIFIC
                )
                ->with([
                    'activities',
                    'units.operationalUnit',
                ])
                ->get();

            foreach ($specificRules as $specificRule) {
                $specificActivityIds = $specificRule->activities
                    ->pluck('activity_id')
                    ->map(fn($id) => (int) $id)
                    ->sort()
                    ->values()
                    ->all();

                if ($newActivityIds !== $specificActivityIds) {
                    continue;
                }

                $operationalUnit = $specificRule
                    ->units
                    ->first()
                    ?->operationalUnit;

                $unitName = $operationalUnit?->identifier
                    ?? $operationalUnit?->name
                    ?? 'unidade operacional';

                throw new \DomainException(
                    'Não é possível atualizar a regra Todos. '
                        . 'A nova composição de atividades é igual à regra '
                        . 'específica configurada para a unidade '
                        . "{$unitName}. "
                        . 'Altere a regra Todos ou revise a regra específica '
                        . 'dessa unidade.'
                );
            }

            /**
             * Atualiza os vínculos das atividades.
             */
            $rule->activities()->delete();

            foreach ($newActivityIds as $activityId) {
                $rule->activities()->create([
                    'activity_id' => $activityId,
                ]);
            }

            return $rule->fresh([
                'activities.activity',
            ]);
        });
    }

    /**
     * Remove uma regra e suas relações.
     */
    public function delete(
        PreventiveProfileRule $rule
    ): void {
        DB::transaction(function () use ($rule): void {
            $rule->units()->delete();

            $rule->activities()->delete();

            $rule->delete();
        });
    }

    /**
     * Remove toda a configuração de uma filial.
     *
     * Remove a regra ALL e todas as regras específicas
     * pertencentes à filial.
     */
    public function deleteBranchConfiguration(
        PreventiveProfileBranch $profileBranch
    ): void {
        DB::transaction(function () use ($profileBranch): void {

            $rules = PreventiveProfileRule::query()
                ->where(
                    'preventive_profile_branch_id',
                    $profileBranch->id
                )
                ->get();

            foreach ($rules as $rule) {
                $rule->units()->delete();
                $rule->activities()->delete();
                $rule->delete();
            }
        });
    }
}

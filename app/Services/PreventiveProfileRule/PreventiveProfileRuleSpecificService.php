<?php

declare(strict_types=1);

namespace App\Services\PreventiveProfileRule;

use App\Enums\PreventiveProfileRuleType;
use App\Models\Activity;
use App\Models\OperationalUnit;
use App\Models\Configuration\Preventive\PreventiveProfile;
use App\Models\Configuration\Preventive\PreventiveProfileRule;
use Illuminate\Support\Facades\DB;

/**
 * Criação e atualização de regras específicas (SPECIFIC) de uma filial.
 */
class PreventiveProfileRuleSpecificService
{
    public function __construct(
        private readonly PreventiveProfileRuleValidationService $validationService,
    ) {}

    /**
     * Cria uma regra específica para uma unidade operacional.
     *
     * A regra específica pertence à mesma filial da regra ALL
     * informada e sobrescreve a regra padrão para a unidade.
     *
     * @param array<string, mixed> $data
     */
    public function createSpecificRule(
        PreventiveProfile $profile,
        PreventiveProfileRule $rule,
        array $data
    ): PreventiveProfileRule {
        return DB::transaction(function () use (
            $profile,
            $rule,
            $data
        ): PreventiveProfileRule {

            $rule->load([
                'preventiveProfileBranch',
                'activities',
            ]);

            /**
             * A regra utilizada como referência deve ser a regra ALL.
             */
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

            /**
             * Garante que a unidade pertence à filial da regra
             * e está ativa.
             */
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

            /**
             * Normaliza as atividades.
             */
            $activityIds = collect($data['activity_ids'])
                ->map(fn($id) => (int) $id)
                ->unique()
                ->values();

            /**
             * Verifica se as atividades existem e estão ativas.
             */
            $validActivityIds = Activity::query()
                ->where('active', true)
                ->whereIn('id', $activityIds)
                ->pluck('id')
                ->map(fn($id) => (int) $id);

            if ($validActivityIds->count() !== $activityIds->count()) {
                throw new \DomainException(
                    'Uma ou mais atividades selecionadas são inválidas ou estão inativas.'
                );
            }

            /**
             * Uma unidade operacional só pode possuir uma regra
             * específica dentro da filial.
             */
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
                    fn($query) => $query->where(
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

            /**
             * Cria a regra específica.
             */
            $specificRule = PreventiveProfileRule::create([
                'preventive_profile_branch_id' => $profileBranch->id,
                'rule_type' => PreventiveProfileRuleType::SPECIFIC->value,
            ]);

            /**
             * Vincula a unidade operacional.
             */
            $specificRule->units()->create([
                'operational_unit_id' => $operationalUnit->id,
            ]);

            /**
             * Vincula as atividades.
             */
            $specificRule->activities()->createMany(
                $validActivityIds
                    ->map(fn($activityId) => [
                        'activity_id' => $activityId,
                    ])
                    ->all()
            );

            return $specificRule->load([
                'preventiveProfileBranch.branch',
                'units.operationalUnit',
                'activities.activity',
            ]);
        });
    }
    /**
     * Exibe uma regra específica.
     */
    public function show(
        PreventiveProfile $profile,
        PreventiveProfileRule $specificRule
    ): PreventiveProfileRule {
        $this->validationService->validateRuleBelongsToProfile(
            $profile,
            $specificRule
        );

        $this->validationService->validateRuleType(
            $specificRule,
            PreventiveProfileRuleType::SPECIFIC
        );

        return $specificRule->load([
            'preventiveProfileBranch.branch',
            'units.operationalUnit',
            'activities.activity',
        ]);
    }

    /**
     * Remove uma regra específica.
     */
    public function delete(
        PreventiveProfile $profile,
        PreventiveProfileRule $specificRule
    ): void {
        DB::transaction(function () use (
            $profile,
            $specificRule
        ): void {
            $this->validationService->validateRuleBelongsToProfile(
                $profile,
                $specificRule
            );

            $this->validationService->validateRuleType(
                $specificRule,
                PreventiveProfileRuleType::SPECIFIC
            );

            $specificRule->units()->delete();

            $specificRule->activities()->delete();

            $specificRule->delete();
        });
    }

    /**
     * Atualiza uma regra específica existente.
     *
     * A regra específica:
     * - permanece vinculada à mesma filial;
     * - permanece vinculada à mesma unidade operacional;
     * - pode alterar somente sua composição de atividades;
     * - não pode possuir a mesma composição da regra Todos;
     * - pode possuir a mesma composição de outra regra específica,
     *   desde que pertença a outra unidade operacional.
     */
    public function updateSpecificRule(
        PreventiveProfile $profile,
        PreventiveProfileRule $specificRule,
        array $data
    ): void {
        DB::transaction(function () use (
            $profile,
            $specificRule,
            $data
        ): void {

            /**
             * --------------------------------------------------------------
             * Validação da regra
             * --------------------------------------------------------------
             */
            $this->validationService->validateRuleBelongsToProfile(
                $profile,
                $specificRule
            );

            if (
                $specificRule->rule_type
                !== PreventiveProfileRuleType::SPECIFIC
            ) {
                throw new \DomainException(
                    'A regra informada não é uma regra específica.'
                );
            }

            /**
             * --------------------------------------------------------------
             * Filial da regra
             * --------------------------------------------------------------
             */
            $specificRule->load([
                'preventiveProfileBranch',
                'units',
                'activities',
            ]);

            $profileBranch = $specificRule->preventiveProfileBranch;

            if (!$profileBranch) {
                throw new \DomainException(
                    'A regra específica não possui uma filial vinculada.'
                );
            }

            /**
             * --------------------------------------------------------------
             * Unidade operacional
             * --------------------------------------------------------------
             *
             * No editar a unidade não deve ser alterada pela interface.
             *
             * Ainda assim, validamos o ID recebido pelo formulário para
             * garantir que ele pertence à mesma filial da regra.
             */
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
                    'A unidade operacional selecionada não pertence à filial da regra.'
                );
            }

            /**
             * --------------------------------------------------------------
             * Verifica duplicidade da unidade
             * --------------------------------------------------------------
             *
             * Uma unidade não pode possuir duas regras específicas
             * dentro da mesma configuração de filial.
             *
             * A própria regra que está sendo editada é ignorada.
             *
             * Importante:
             * Não verificamos a composição das atividades aqui.
             *
             * Portanto:
             *
             * PDV 01 -> [atividade 1, atividade 2]
             * PDV 02 -> [atividade 1, atividade 2]
             *
             * é permitido.
             */
            $alreadyExists = PreventiveProfileRule::query()
                ->where(
                    'preventive_profile_branch_id',
                    $profileBranch->id
                )
                ->where(
                    'rule_type',
                    PreventiveProfileRuleType::SPECIFIC
                )
                ->whereKeyNot($specificRule->id)
                ->whereHas(
                    'units',
                    function ($query) use ($operationalUnit) {
                        $query->where(
                            'operational_unit_id',
                            $operationalUnit->id
                        );
                    }
                )
                ->exists();

            if ($alreadyExists) {
                throw new \DomainException(
                    'Já existe uma regra específica configurada para esta unidade.'
                );
            }

            /**
             * --------------------------------------------------------------
             * Normaliza as atividades
             * --------------------------------------------------------------
             */
            $activityIds = collect($data['activity_ids'])
                ->map(fn($id) => (int) $id)
                ->unique()
                ->values();

            if ($activityIds->isEmpty()) {
                throw new \DomainException(
                    'Pelo menos uma atividade deve ser selecionada.'
                );
            }

            /**
             * --------------------------------------------------------------
             * Valida as atividades
             * --------------------------------------------------------------
             */
            $validActivityIds = Activity::query()
                ->where('active', true)
                ->whereIn('id', $activityIds)
                ->pluck('id')
                ->map(fn($id) => (int) $id);

            if (
                $validActivityIds->count()
                !== $activityIds->count()
            ) {
                throw new \DomainException(
                    'Uma ou mais atividades selecionadas são inválidas ou estão inativas.'
                );
            }

            /**
             * --------------------------------------------------------------
             * Regra Todos da mesma configuração de filial
             * --------------------------------------------------------------
             *
             * A comparação deve ser feita somente contra a regra Todos
             * pertencente à mesma PreventiveProfileBranch.
             */
            $baseRule = PreventiveProfileRule::query()
                ->where(
                    'preventive_profile_branch_id',
                    $profileBranch->id
                )
                ->where(
                    'rule_type',
                    PreventiveProfileRuleType::ALL
                )
                ->with('activities')
                ->first();

            if ($baseRule) {
                $baseActivityIds = $baseRule->activities
                    ->pluck('activity_id')
                    ->map(fn($id) => (int) $id)
                    ->sort()
                    ->values()
                    ->all();

                $specificActivityIds = $validActivityIds
                    ->sort()
                    ->values()
                    ->all();

                /**
                 * Uma regra específica não pode ser exatamente igual
                 * à regra Todos.
                 */
                if ($baseActivityIds === $specificActivityIds) {
                    throw new \DomainException(
                        'A regra específica não pode possuir a mesma composição de atividades da regra Todos.'
                    );
                }
            }

            /**
             * --------------------------------------------------------------
             * Atualiza os vínculos
             * --------------------------------------------------------------
             *
             * A unidade continua sendo a mesma.
             * Removemos e recriamos os vínculos para manter a composição
             * sincronizada com o formulário.
             */
            $specificRule->units()->delete();

            $specificRule->activities()->delete();

            $specificRule->units()->create([
                'operational_unit_id' => $operationalUnit->id,
            ]);

            $specificRule->activities()->createMany(
                $validActivityIds
                    ->map(fn($activityId) => [
                        'activity_id' => $activityId,
                    ])
                    ->all()
            );
        });
    }
}

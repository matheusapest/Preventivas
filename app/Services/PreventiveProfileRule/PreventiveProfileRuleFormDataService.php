<?php

declare(strict_types=1);

namespace App\Services\PreventiveProfileRule;

use App\Enums\PreventiveProfileRuleType;
use App\Models\Activity;
use App\Models\ActivityCategory;
use App\Models\OperationalUnit;
use App\Models\PreventiveProfile;
use App\Models\PreventiveProfileRule;

/**
 * Monta os dados necessários para os formulários de criação/edição
 * de regras de perfil preventivo.
 */
class PreventiveProfileRuleFormDataService
{
    public function __construct(
        private readonly PreventiveProfileRuleQueryService $queryService,
    ) {}

    /**
     * Retorna os dados necessários para os formulários.
     */
    public function getFormData(
        PreventiveProfile $profile,
        ?PreventiveProfileRule $rule = null
    ): array {
        $profile->load([
            'preventiveType',
        ]);

        $unitTypeId = $profile->preventiveType?->unit_type_id;

        $operationalUnits = collect();
        $availableOperationalUnits = collect();
        $allRule = null;
        $specificRules = collect();
        $profileBranch = null;

        /*
        |--------------------------------------------------------------------------
        | CREATE
        |--------------------------------------------------------------------------
        |
        | Ainda não existe uma regra.
        |
        | Nesse caso, carregamos somente as filiais:
        |
        | - vinculadas ao perfil;
        | - ativas;
        | - que possuem pelo menos uma unidade operacional ativa;
        | - compatível com o tipo de unidade da preventiva;
        | - que ainda não possuem uma regra ALL.
        |
        */

        if (!$rule) {
            $branches = $profile->branches()
                ->whereDoesntHave('rules', function ($query) {
                    $query
                        ->where(
                            'rule_type',
                            PreventiveProfileRuleType::ALL->value
                        )
                        ->whereHas('activities');
                })
                ->whereHas('branch', function ($query) use ($unitTypeId) {
                    $query
                        ->active()
                        ->when(
                            $unitTypeId,
                            fn($query) => $query->whereHas(
                                'operationalUnits',
                                function ($query) use ($unitTypeId) {
                                    $query
                                        ->active()
                                        ->where(
                                            'unit_type_id',
                                            $unitTypeId
                                        );
                                }
                            )
                        );
                })
                ->with('branch')
                ->get();

            $activityCategories = ActivityCategory::query()
                ->where('active', true)
                ->whereHas('activities', function ($query) use ($profile) {
                    $query
                        ->where('active', true)
                        ->where(
                            'preventive_type_id',
                            $profile->preventive_type_id
                        );
                })
                ->with([
                    'activities' => function ($query) use ($profile) {
                        $query
                            ->where('active', true)
                            ->where(
                                'preventive_type_id',
                                $profile->preventive_type_id
                            )
                            ->orderBy('name');
                    },
                ])
                ->orderBy('name')
                ->get();

            return [
                'branches' => $branches,
                'profileBranch' => null,
                'allRule' => null,
                'specificRules' => collect(),
                'operationalUnits' => collect(),
                'availableOperationalUnits' => collect(),
                'activityCategories' => $activityCategories,
                'selectedActivityIds' => [],
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | EDIT
        |--------------------------------------------------------------------------
        |
        | A regra recebida pelo controller deve ser a regra ALL
        | da filial que estamos editando.
        |
        */

        $rule->load([
            'preventiveProfileBranch.branch',
            'activities.activity',
        ]);

        $allRule = $rule;

        $profileBranch = $rule->preventiveProfileBranch;

        $branchId = $profileBranch?->branch_id;

        /*
        |--------------------------------------------------------------------------
        | FILIAIS
        |--------------------------------------------------------------------------
        |
        | No edit mantemos a filial atualmente configurada no formulário,
        | mesmo que ela tenha perdido posteriormente sua última unidade
        | operacional compatível.
        |
        | As demais filiais seguem a mesma regra de elegibilidade do create.
        |
        */

        $branches = $profile->branches()
            ->where(function ($query) use (
                $branchId,
                $unitTypeId
            ) {
                $query
                    ->where('branch_id', $branchId)
                    ->orWhere(function ($query) use ($unitTypeId) {
                        $query->whereHas(
                            'branch',
                            function ($query) use ($unitTypeId) {
                                $query
                                    ->active()
                                    ->when(
                                        $unitTypeId,
                                        fn($query) => $query->whereHas(
                                            'operationalUnits',
                                            function ($query) use ($unitTypeId) {
                                                $query
                                                    ->active()
                                                    ->where(
                                                        'unit_type_id',
                                                        $unitTypeId
                                                    );
                                            }
                                        )
                                    );
                            }
                        );
                    });
            })
            ->with('branch')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | REGRAS ESPECÍFICAS
        |--------------------------------------------------------------------------
        |
        | Carrega todas as regras específicas da mesma filial.
        |
        */

        if ($profileBranch) {
            $specificRules = PreventiveProfileRule::query()
                ->where(
                    'preventive_profile_branch_id',
                    $profileBranch->id
                )
                ->where(
                    'rule_type',
                    PreventiveProfileRuleType::SPECIFIC->value
                )
                ->with([
                    'units.operationalUnit',
                    'activities.activity',
                ])
                ->orderBy('id')
                ->get();
        }

        /*
|--------------------------------------------------------------------------
| UNIDADES OPERACIONAIS
|--------------------------------------------------------------------------
|
| Unidades que ainda não possuem regra específica.
|
| Além de pertencerem à filial, devem possuir o mesmo tipo
| de unidade definido pela preventiva do perfil.
|
| Estas serão utilizadas no modal de criação.
|
*/

        if ($profileBranch) {
            $availableOperationalUnits =
                $this->queryService->getAvailableOperationalUnits(
                    $profileBranch,
                    $unitTypeId
                );
        }

        /*
        |--------------------------------------------------------------------------
        | UNIDADES PARA EXIBIÇÃO
        |--------------------------------------------------------------------------
        |
        | Mantemos também as unidades vinculadas às regras específicas.
        |
        | Isso permite que a tela continue exibindo corretamente as unidades
        | que já possuem regras.
        |
        */

        if ($branchId) {
            $operationalUnits = OperationalUnit::query()
                ->active()
                ->with([
                    'branch',
                    'unitType',
                ])
                ->where('branch_id', $branchId)
                ->when(
                    $unitTypeId,
                    fn($query) => $query->where(
                        'unit_type_id',
                        $unitTypeId
                    )
                )
                ->orderBy('identifier')
                ->get();
        }

        /**
         * |--------------------------------------------------------------------------
         * | ATIVIDADES
         * |--------------------------------------------------------------------------
         *
         * | Atividades disponíveis para configuração.
         * |
         * | São carregadas somente as atividades pertencentes
         * | ao tipo de preventiva do perfil.
         *
         */

        $activities = Activity::query()
            ->where(
                'preventive_type_id',
                $profile->preventive_type_id
            )
            ->where('active', true)
            ->orderBy('name')
            ->get([
                'id',
                'name',
            ]);

        $activityCategories = ActivityCategory::query()
            ->where('active', true)
            ->whereHas('activities', function ($query) use ($profile) {
                $query
                    ->where('active', true)
                    ->where(
                        'preventive_type_id',
                        $profile->preventive_type_id
                    );
            })
            ->with([
                'activities' => function ($query) use ($profile) {
                    $query
                        ->where('active', true)
                        ->where(
                            'preventive_type_id',
                            $profile->preventive_type_id
                        )
                        ->orderBy('name');
                },
            ])
            ->orderBy('name')
            ->get();

        /**
         * |--------------------------------------------------------------------------
         * | ATIVIDADES DA REGRA ALL
         * |--------------------------------------------------------------------------
         */

        $selectedActivityIds = $allRule
            ->activities
            ->pluck('activity_id')
            ->map(fn($id) => (int) $id)
            ->all();

        return [
            'branches' => $branches,
            'profileBranch' => $profileBranch,
            'allRule' => $allRule,
            'specificRules' => $specificRules,

            /**
             * Todas as unidades da filial.
             *
             * Usadas pela tela para exibição/contexto.
             */
            'operationalUnits' => $operationalUnits,

            /**
             * Somente unidades que ainda não possuem regra específica.
             *
             * Usadas pelo modal de criação.
             */
            'availableOperationalUnits' => $availableOperationalUnits,

            'activities' => $activities,
            'activityCategories' => $activityCategories,
            'selectedActivityIds' => $selectedActivityIds,
        ];
    }
}

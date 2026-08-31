<?php

declare(strict_types=1);

namespace App\Services\PreventiveProfileRule;

use App\Enums\PreventiveProfileRuleType;
use App\Models\OperationalUnit;
use App\Models\Configuration\Preventive\PreventiveProfile;
use App\Models\Configuration\Preventive\PreventiveProfileBranch;
use App\Models\Configuration\Preventive\PreventiveProfileRule;

/**
 * Consultas e listagens relacionadas às regras de perfil preventivo.
 */
class PreventiveProfileRuleQueryService
{
    /**
     * Retorna os dados necessários para visualizar uma regra.
     */
    public function getShowData(
        PreventiveProfileRule $rule
    ): PreventiveProfileRule {
        return $rule->load([
            'preventiveProfileBranch.branch',
            'units.operationalUnit.branch',
            'units.operationalUnit.unitType',
            'activities.activity',
        ]);
    }

    /**
     * Retorna uma regra com todas as relações necessárias
     * para a tela de visualização.
     */
    public function getRuleForShow(
        PreventiveProfileRule $rule
    ): PreventiveProfileRule {
        return $rule->load([
            'preventiveProfileBranch.branch',

            // Todas as regras da filial
            'preventiveProfileBranch.rules.activities.activity',
            'preventiveProfileBranch.rules.units.operationalUnit.branch',
            'preventiveProfileBranch.rules.units.operationalUnit.unitType',
        ]);
    }
    /**
     * Retorna as regras de um perfil.
     */
    public function getRules(
        PreventiveProfile $profile,
        array $filters = []
    ) {
        return PreventiveProfileRule::query()
            ->whereHas(
                'preventiveProfileBranch',
                fn($query) => $query->where(
                    'preventive_profile_id',
                    $profile->id
                )
            )
            ->with([
                'preventiveProfileBranch.branch',
                'units.operationalUnit',
                'activities.activity',
            ])
            ->orderBy('id')
            ->get();
    }

    /**
     * Retorna as configurações de regras agrupadas por filial.
     *
     * @param array<string, mixed> $filters
     */
    public function getBranchConfigurations(
        PreventiveProfile $profile,
        array $filters = []
    ) {
        $branches = $profile->branches()
            ->with([
                'branch',
                'rules.activities.activity',
                'rules.units.operationalUnit',
            ])
            ->when(
                !empty($filters['branch_id']),
                fn($query) => $query->where(
                    'branch_id',
                    (int) $filters['branch_id']
                )
            )
            ->when(
                isset($filters['status']) &&
                    $filters['status'] !== '',
                function ($query) use ($filters) {
                    if ($filters['status'] === 'configured') {
                        $query->whereHas(
                            'rules',
                            fn($ruleQuery) => $ruleQuery
                                ->where('rule_type', 'all')
                        );
                    }

                    if ($filters['status'] === 'pending') {
                        $query->whereDoesntHave(
                            'rules',
                            fn($ruleQuery) => $ruleQuery
                                ->where('rule_type', 'all')
                        );
                    }
                }
            )
            ->orderBy('id')
            ->paginate(15)
            ->withQueryString();

        $branches->getCollection()->transform(
            function ($profileBranch) {
                $branchRules = $profileBranch->rules;

                $allRule = $branchRules->first(
                    fn($rule) =>
                    $rule->rule_type->value === 'all'
                );

                $specificRules = $branchRules->filter(
                    fn($rule) =>
                    $rule->rule_type->value === 'specific'
                );

                return [
                    'profileBranch' => $profileBranch,
                    'branch' => $profileBranch->branch,
                    'allRule' => $allRule,
                    'specificRules' => $specificRules,
                    'specificCount' => $specificRules->count(),
                    'activityCount' => $allRule
                        ? $allRule->activities->count()
                        : 0,
                    'configured' => $allRule !== null,
                ];
            }
        );

        return $branches;
    }

    /**
     * Retorna os dados gerais do resumo das regras do perfil.
     *
     * Os dados não sofrem influência dos filtros da listagem.
     *
     * @return array{
     *     totalBranches: int,
     *     configuredBranches: int,
     *     pendingBranches: int,
     *     totalSpecificRules: int
     * }
     */
    public function getSummaryData(
        PreventiveProfile $profile
    ): array {
        $totalBranches = $profile->branches()->count();

        $configuredBranches = $this->countConfiguredBranches($profile);

        $totalSpecificRules = PreventiveProfileRule::query()
            ->whereHas(
                'preventiveProfileBranch',
                fn($query) => $query->where(
                    'preventive_profile_id',
                    $profile->id
                )
            )
            ->where('rule_type', 'specific')
            ->count();

        return [
            'totalBranches' => $totalBranches,
            'configuredBranches' => $configuredBranches,
            'pendingBranches' => $totalBranches - $configuredBranches,
            'totalSpecificRules' => $totalSpecificRules,
        ];
    }
    /**
     * Retorna a quantidade de filiais com regra ALL configurada.
     */
    public function countConfiguredBranches(
        PreventiveProfile $profile
    ): int {
        return $profile->branches()
            ->whereHas(
                'rules',
                function ($query) {
                    $query
                        ->where('rule_type', 'all')
                        ->whereHas('activities');
                }
            )
            ->count();
    }

    /**
     * Retorna as unidades operacionais ativas da filial
     * que ainda não possuem uma regra específica.
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, OperationalUnit>
     */
    public function getAvailableOperationalUnits(
        PreventiveProfileBranch $profileBranch,
        ?int $unitTypeId = null
    ) {
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
                fn($specificRule) =>
                $specificRule->units->pluck('operational_unit_id')
            )
            ->unique()
            ->values();

        return OperationalUnit::query()
            ->active()
            ->with([
                'branch',
                'unitType',
            ])
            ->where(
                'branch_id',
                $profileBranch->branch_id
            )
            ->when(
                $unitTypeId,
                fn($query) => $query->where(
                    'unit_type_id',
                    $unitTypeId
                )
            )
            ->when(
                $usedOperationalUnitIds->isNotEmpty(),
                fn($query) => $query->whereNotIn(
                    'id',
                    $usedOperationalUnitIds
                )
            )
            ->orderBy('identifier')
            ->get();
    }

}

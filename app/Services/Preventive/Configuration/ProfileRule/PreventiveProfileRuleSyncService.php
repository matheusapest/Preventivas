<?php

declare(strict_types=1);

namespace App\Services\Preventive\Configuration\ProfileRule;

use App\Models\Configuration\Preventive\PreventiveProfileRule;

/**
 * Sincronização de unidades e atividades vinculadas a uma regra.
 */
class PreventiveProfileRuleSyncService
{
    /**
     * Sincroniza as unidades da regra.
     *
     * @param array<int, mixed> $unitIds
     */
    public function syncUnits(
        PreventiveProfileRule $rule,
        array $unitIds
    ): void {
        $rule->units()->delete();

        foreach (
            array_unique(
                array_map('intval', $unitIds)
            ) as $unitId
        ) {
            $rule->units()->create([
                'operational_unit_id' => $unitId,
            ]);
        }
    }

    /**
     * Sincroniza as atividades da regra.
     *
     * @param array<int, mixed> $activityIds
     */
    public function syncActivities(
        PreventiveProfileRule $rule,
        array $activityIds
    ): void {
        $rule->activities()->delete();

        foreach (
            array_unique(
                array_map('intval', $activityIds)
            ) as $activityId
        ) {
            $rule->activities()->create([
                'activity_id' => $activityId,
            ]);
        }
    }

    /**
     * Carrega a regra com suas relações.
     */
    public function loadRule(
        PreventiveProfileRule $rule
    ): PreventiveProfileRule {
        return $rule->load([
            'preventiveProfileBranch.branch',
            'units.operationalUnit',
            'activities.activity',
        ]);
    }
}

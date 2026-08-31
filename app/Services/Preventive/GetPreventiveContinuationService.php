<?php

declare(strict_types=1);

namespace App\Services\Preventive;

use App\Enums\CycleReviewStatusEnum;
use App\Enums\StatusCycleEnum;
use App\Enums\StatusPreventiveEnum;
use App\Models\Preventive;
use App\Models\PreventiveCycle;
use App\Models\PreventiveSnapshot;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class GetPreventiveContinuationService
{
    /**
     * Monta os dados necessários para a tela de continuidade
     * de uma preventiva reprovada.
     *
     * Nenhum Cycle, unidade, atividade ou snapshot é criado aqui.
     */
    public function execute(
        Preventive $preventive
    ): array {
        $this->validatePreventive($preventive);

        $cycle = $this->getCurrentCycle($preventive);

        $this->validateCycle($cycle);

        /*
         * Dados existentes no Cycle anterior.
         *
         * Utilizados exclusivamente para identificar
         * as unidades que possuem pendências.
         */
        $cycle->load([
            'units.snapshotUnit',
            'units.activities.snapshotRuleActivity',
            'units.activityResponses',
        ]);

        $units = $this->buildUnits($cycle);

        $pendingUnits = $units
            ->filter(
                fn (array $unit): bool =>
                    $unit['status'] === 'pending'
            )
            ->values();

        /*
         * Todas as unidades disponíveis para uma nova
         * seleção devem vir do snapshot da preventiva.
         *
         * Não utilizamos as unidades do Cycle anterior
         * como fonte dessa lista.
         */
        $snapshot = $this->getSnapshot($preventive);

        $availableUnits = $this->buildAvailableUnits($snapshot);

        return [
            'preventive' => $preventive,

            'cycle' => $cycle,

            /*
             * Unidades que participaram do Cycle anterior.
             */
            'units' => $units,

            /*
             * Unidades que possuem pendências no Cycle anterior.
             */
            'pendingUnits' => $pendingUnits,

            'hasPendingUnits' =>
                $pendingUnits->isNotEmpty(),

            /*
             * TODAS as unidades existentes no snapshot.
             *
             * Utilizadas pelo agregador da continuidade.
             */
            'availableUnits' => $availableUnits,

            /*
             * Motivo informado pelo gestor na reprovação.
             */
            'reviewObservation' =>
                $cycle->review_observation,
        ];
    }

    /**
     * A continuidade somente pode ser iniciada quando
     * a preventiva estiver novamente em execução após
     * uma reprovação.
     */
    private function validatePreventive(
        Preventive $preventive
    ): void {
        if (
            $preventive->status !==
            StatusPreventiveEnum::IN_PROGRESS
        ) {
            throw ValidationException::withMessages([
                'preventive' =>
                    'A preventiva não está disponível para continuidade.',
            ]);
        }
    }

    /**
     * Localiza o Cycle atualmente associado à preventiva.
     */
    private function getCurrentCycle(
        Preventive $preventive
    ): PreventiveCycle {
        $cycle = $preventive->cycles()
            ->where(
                'sequence',
                $preventive->current_cycle
            )
            ->first();

        if (! $cycle) {
            throw ValidationException::withMessages([
                'cycle' =>
                    'O ciclo atual da preventiva não foi encontrado.',
            ]);
        }

        return $cycle;
    }

    /**
     * A continuidade deve partir de um Cycle finalizado
     * e reprovado pelo gestor.
     */
    private function validateCycle(
        PreventiveCycle $cycle
    ): void {
        if (
            $cycle->status !==
            StatusCycleEnum::FINISHED
        ) {
            throw ValidationException::withMessages([
                'cycle' =>
                    'O ciclo atual ainda não foi finalizado.',
            ]);
        }

        if (
            $cycle->review_status !==
            CycleReviewStatusEnum::REJECTED
        ) {
            throw ValidationException::withMessages([
                'cycle' =>
                    'O ciclo atual não está reprovado para continuidade.',
            ]);
        }
    }

    /**
     * Organiza as unidades do Cycle anterior.
     *
     * Essas informações são utilizadas para identificar
     * as pendências existentes.
     */
    private function buildUnits(
        PreventiveCycle $cycle
    ): Collection {
        return $cycle->units
            ->map(function ($cycleUnit): array {

                $responses = $cycleUnit
                    ->activityResponses
                    ->keyBy(
                        'snapshot_rule_activity_id'
                    );

                $activities = $cycleUnit
                    ->activities
                    ->map(
                        function ($cycleUnitActivity) use ($responses): array {

                            $activity =
                                $cycleUnitActivity
                                    ->snapshotRuleActivity;

                            $response = $responses->get(
                                $cycleUnitActivity
                                    ->snapshot_rule_activity_id
                            );

                            return [
                                'id' =>
                                    $cycleUnitActivity->id,

                                'snapshot_rule_activity_id' =>
                                    $cycleUnitActivity
                                        ->snapshot_rule_activity_id,

                                'name' =>
                                    $activity?->activity_name,

                                'description' =>
                                    $activity?->activity_description,

                                'type' =>
                                    $activity?->activity_type,

                                'answered' =>
                                    $response !== null,

                                'response' =>
                                    $response,
                            ];
                        }
                    )
                    ->values();

                $totalActivities =
                    $activities->count();

                $answeredActivities =
                    $activities
                        ->where('answered', true)
                        ->count();

                $status =
                    $totalActivities > 0
                    && $answeredActivities >= $totalActivities
                        ? 'completed'
                        : 'pending';

                return [
                    'id' =>
                        $cycleUnit->id,

                    'snapshot_unit_id' =>
                        $cycleUnit->snapshot_unit_id,

                    'operational_unit_id' =>
                        $cycleUnit->operational_unit_id,

                    'name' =>
                        $cycleUnit
                            ->snapshotUnit
                            ?->operational_unit_name,

                    'identifier' =>
                        $cycleUnit
                            ->snapshotUnit
                            ?->operational_unit_identifier,

                    'status' =>
                        $status,

                    'total_activities' =>
                        $totalActivities,

                    'answered_activities' =>
                        $answeredActivities,

                    'activities' =>
                        $activities,
                ];
            })
            ->values();
    }

    /**
     * Localiza o snapshot congelado da preventiva.
     */
    private function getSnapshot(
        Preventive $preventive
    ): PreventiveSnapshot {
        $snapshot = PreventiveSnapshot::query()
            ->where(
                'preventive_id',
                $preventive->id
            )
            ->with([
                'rules.units',
            ])
            ->first();

        if (! $snapshot) {
            throw ValidationException::withMessages([
                'snapshot' =>
                    'A preventiva não possui um snapshot de configuração.',
            ]);
        }

        return $snapshot;
    }

    /**
     * Monta todas as unidades disponíveis para seleção
     * na continuidade.
     *
     * A origem é exclusivamente o snapshot congelado.
     */
    private function buildAvailableUnits(
        PreventiveSnapshot $snapshot
    ): Collection {
        return $snapshot->rules
            ->flatMap(
                fn ($rule) => $rule->units
            )
            ->unique('operational_unit_id')
            ->sortBy('operational_unit_identifier')
            ->values()
            ->map(
                function ($unit): array {
                    return [
                        'operational_unit_id' =>
                            $unit->operational_unit_id,

                        'name' =>
                            $unit->operational_unit_name,

                        'identifier' =>
                            $unit->operational_unit_identifier,
                    ];
                }
            )
            ->values();
    }
}

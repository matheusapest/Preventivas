<?php

declare(strict_types=1);

namespace App\Services\Preventive\Validation;

use App\Enums\StatusPreventiveEnum;
use App\Models\Preventive\Preventive;
use App\Models\Preventive\PreventiveCycle;
use App\Models\Preventive\PreventiveCycleUnit;
use Illuminate\Validation\ValidationException;

class GetPreventiveValidationService
{
    /**
     * Retorna os dados necessários para a tela
     * de validação da preventiva.
     *
     * A análise utiliza exclusivamente os dados
     * congelados no ciclo da preventiva.
     */
    public function execute(Preventive $preventive): array
    {
        $this->validatePreventive($preventive);

        $cycle = $this->getCurrentCycle($preventive);

        $this->loadCycleData($cycle);

        $units = $cycle->units
            ->map(
                fn (PreventiveCycleUnit $cycleUnit): array =>
                $this->buildUnitSummary($cycleUnit)
            )
            ->values();

        return [
            'preventive' => $preventive,
            'cycle' => $cycle,
            'units' => $units,
            'summary' => $this->buildGeneralSummary($units),
        ];
    }

    /**
     * Valida se a preventiva pode ser analisada
     * pelo gestor.
     */
    private function validatePreventive(
        Preventive $preventive
    ): void {
        if (
            $preventive->status !==
            StatusPreventiveEnum::PENDING_APPROVAL
        ) {
            throw ValidationException::withMessages([
                'preventive' =>
                    'A preventiva não está aguardando validação.',
            ]);
        }
    }

    /**
     * Localiza o ciclo atual da preventiva.
     */
    private function getCurrentCycle(
        Preventive $preventive
    ): PreventiveCycle {
        $cycle = PreventiveCycle::query()
            ->where(
                'preventive_id',
                $preventive->id
            )
            ->where(
                'sequence',
                $preventive->current_cycle
            )
            ->first();

        if (! $cycle) {
            throw ValidationException::withMessages([
                'preventive' =>
                    'O ciclo atual da preventiva não foi encontrado.',
            ]);
        }

        return $cycle;
    }

    /**
     * Carrega toda a estrutura necessária para
     * montar o resumo da validação.
     */
    private function loadCycleData(
        PreventiveCycle $cycle
    ): void {
        $cycle->load([
            'units.snapshotUnit',
            'units.activities.snapshotRuleActivity',
            'units.activityResponses',
        ]);
    }

    /**
     * Monta o resumo individual de uma unidade.
     */
    private function buildUnitSummary(
        PreventiveCycleUnit $cycleUnit
    ): array {
        $snapshotUnit = $cycleUnit->snapshotUnit;

        $activities = $this->buildActivities($cycleUnit);

        $totalActivities = $activities->count();

        $answeredActivities = $activities
            ->where('answered', true)
            ->count();

        $pendingActivities =
            $totalActivities - $answeredActivities;

        $completed =
            $totalActivities > 0 &&
            $pendingActivities === 0;

        $responses = $activities
            ->pluck('response')
            ->filter();

        $unitStatus = $this->resolveUnitStatus(
            $responses,
            $completed
        );

        $failedComponents =
            $this->collectFailedComponents($responses);

        $observations =
            $this->collectObservations($responses);

        return [
            'cycle_unit' =>
                $cycleUnit,

            'snapshot_unit' =>
                $snapshotUnit,

            'unit_name' =>
                $snapshotUnit?->operational_unit_name
                    ?? 'Unidade operacional',

            'operational_unit_identifier' =>
                $snapshotUnit?->operational_unit_identifier,

            'unit_type_name' =>
                $snapshotUnit?->unit_type_name,

            'operational_profile_name' =>
                $snapshotUnit?->operational_profile_name,

            'status' =>
                $unitStatus,

            'activities' =>
                $activities,

            'failed_components' =>
                $failedComponents,

            'observations' =>
                $observations,

            'progress' => [
                'total' =>
                    $totalActivities,

                'answered' =>
                    $answeredActivities,

                'pending' =>
                    $pendingActivities,

                'completed' =>
                    $completed,
            ],
        ];
    }

    /**
     * Monta as atividades e associa a resposta
     * correspondente de cada atividade.
     *
     * Os dados de apresentação da atividade são
     * extraídos diretamente do snapshot.
     */
    private function buildActivities(
        PreventiveCycleUnit $cycleUnit
    ) {
        return $cycleUnit->activities
            ->map(
                function ($cycleUnitActivity) use ($cycleUnit): array {

                    /**
                     * Atividade congelada no snapshot.
                     */
                    $activity =
                        $cycleUnitActivity->snapshotRuleActivity;

                    /**
                     * Localiza a resposta correspondente
                     * à atividade dentro da CycleUnit.
                     */
                    $response =
                        $cycleUnit->activityResponses
                            ->firstWhere(
                                'snapshot_rule_activity_id',
                                $cycleUnitActivity
                                    ->snapshot_rule_activity_id
                            );

                    return [
                        'cycle_unit_activity' =>
                            $cycleUnitActivity,

                        /**
                         * Mantém o objeto completo do snapshot
                         * caso a Blade precise de outros campos.
                         */
                        'activity' =>
                            $activity,

                        /**
                         * Dados normalizados para apresentação.
                         */
                        'activity_name' =>
                            $activity?->activity_name,

                        'activity_description' =>
                            $activity?->activity_description,

                        'activity_type' =>
                            $activity?->activity_type,

                        /**
                         * Resposta registrada no Cycle.
                         */
                        'response' =>
                            $response,

                        /**
                         * Indica se a atividade foi respondida.
                         */
                        'answered' =>
                            $response !== null,

                        /**
                         * Status da resposta.
                         */
                        'status' =>
                            $response
                                ? (
                                    $response->final_status
                                    ?? $response->result
                                )
                                : 'pending',
                    ];
                }
            )
            ->values();
    }

    /**
     * Determina a situação da unidade.
     *
     * A unidade só é considerada conforme quando
     * todas as atividades foram respondidas e nenhuma
     * resposta apresentou não conformidade.
     */
    private function resolveUnitStatus(
        $responses,
        bool $completed
    ): string {
        if (! $completed) {
            return 'pending';
        }

        $hasNonConformity = $responses
            ->contains(
                fn ($response): bool =>
                $response->result === 'nao_conforme'
            );

        return $hasNonConformity
            ? 'nao_conforme'
            : 'conforme';
    }

    /**
     * Extrai os componentes que apresentaram falha
     * nas respostas da unidade.
     */
    private function collectFailedComponents(
        $responses
    ): array {
        $components = [];

        foreach ($responses as $response) {
            $responseData = $response->response_data;

            if (! is_array($responseData)) {
                continue;
            }

            foreach ($responseData as $component) {
                if (is_array($component)) {
                    $components[] = $component;
                }
            }
        }

        return $components;
    }

    /**
     * Extrai as observações registradas nas respostas.
     */
    private function collectObservations(
        $responses
    ): array {
        return $responses
            ->pluck('observation')
            ->filter(
                fn ($observation): bool =>
                is_string($observation) &&
                trim($observation) !== ''
            )
            ->values()
            ->all();
    }

    /**
     * Monta o resumo geral da preventiva.
     */
    private function buildGeneralSummary($units): array
    {
        $totalUnits = $units->count();

        $completedUnits = $units
            ->filter(
                fn (array $unit): bool =>
                $unit['progress']['completed']
            )
            ->count();

        $conformingUnits = $units
            ->where('status', 'conforme')
            ->count();

        $nonConformingUnits = $units
            ->where('status', 'nao_conforme')
            ->count();

        $pendingUnits = $units
            ->filter(
                fn (array $unit): bool =>
                ! $unit['progress']['completed']
            )
            ->count();

        $totalActivities = $units->sum(
            fn (array $unit): int =>
            $unit['progress']['total']
        );

        $answeredActivities = $units->sum(
            fn (array $unit): int =>
            $unit['progress']['answered']
        );

        $pendingActivities = $units->sum(
            fn (array $unit): int =>
            $unit['progress']['pending']
        );

        /*
        |--------------------------------------------------------------------------
        | Componentes com falha
        |--------------------------------------------------------------------------
        */

        $failedComponents = $units
            ->flatMap(
                fn (array $unit): array =>
                $unit['failed_components'] ?? []
            )
            ->values()
            ->all();

        /*
        |--------------------------------------------------------------------------
        | Observações
        |--------------------------------------------------------------------------
        */

        $observations = $units
            ->flatMap(
                fn (array $unit): array =>
                $unit['observations'] ?? []
            )
            ->filter(
                fn ($observation): bool =>
                is_string($observation) &&
                trim($observation) !== ''
            )
            ->values()
            ->all();

        return [
            'total_units' =>
                $totalUnits,

            'completed_units' =>
                $completedUnits,

            'conforming_units' =>
                $conformingUnits,

            'non_conforming_units' =>
                $nonConformingUnits,

            'pending_units' =>
                $pendingUnits,

            'total_activities' =>
                $totalActivities,

            'answered_activities' =>
                $answeredActivities,

            'pending_activities' =>
                $pendingActivities,

            'completed' =>
                $totalActivities > 0 &&
                $pendingActivities === 0,

            'failed_components' =>
                $failedComponents,

            'observations' =>
                $observations,
        ];
    }
}

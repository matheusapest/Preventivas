<?php

declare(strict_types=1);

namespace App\Services\Preventive;

use App\Models\Preventive;
use App\Models\PreventiveActivityResponse;
use App\Models\PreventiveCycle;
use App\Models\PreventiveCycleUnit;
use Illuminate\Support\Collection;

class GetPreventiveShowDetailsService
{
    /**
     * Retorna todos os dados necessários para a visualização
     * completa da preventiva.
     *
     * O serviço é exclusivamente de apresentação.
     *
     * Não altera a preventiva.
     * Não altera ciclos.
     * Não aprova.
     * Não reprova.
     */
    public function execute(Preventive $preventive): array
    {
        $cycles = $this->getCycles($preventive);

        $cycleData = $cycles
            ->map(
                fn(PreventiveCycle $cycle): array =>
                $this->buildCycleSummary($cycle)
            )
            ->values();

        return [
            'preventive' => $preventive,
            'cycles' => $cycleData,
        ];
    }

    /**
     * Carrega todos os ciclos da preventiva.
     */
    private function getCycles(
        Preventive $preventive
    ): Collection {
        return $preventive
            ->cycles()
            ->orderBy('sequence')
            ->with([
                'units.snapshotUnit',
                'units.activities.snapshotRuleActivity',
                'units.activityResponses',
            ])
            ->get();
    }

    /**
     * Monta os dados de apresentação de um ciclo.
     */
    private function buildCycleSummary(
        PreventiveCycle $cycle
    ): array {
        $units = $cycle->units
            ->map(
                fn(PreventiveCycleUnit $cycleUnit): array =>
                $this->buildUnitSummary($cycleUnit)
            )
            ->values();

        return [
            'cycle' => $cycle,

            'sequence' => $cycle->sequence,

            'units' => $units,

            'summary' => $this->buildGeneralSummary($units),
        ];
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
            $totalActivities -
            $answeredActivities;

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
     * O status da atividade representa o resultado
     * da inspeção:
     *
     * conforme
     * nao_conforme
     *
     * O final_status representa a situação final
     * da tratativa:
     *
     * Operacional
     * Resolvido
     * Pendente
     */
    private function buildActivities(
        PreventiveCycleUnit $cycleUnit
    ): Collection {
        return $cycleUnit->activities
            ->map(
                function ($cycleUnitActivity) use ($cycleUnit): array {

                    $activity =
                        $cycleUnitActivity->snapshotRuleActivity;

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

                        'activity' =>
                        $activity,

                        'response' =>
                        $response,

                        'answered' =>
                        $response !== null,

                        /*
                         * Resultado da inspeção.
                         *
                         * Usado pela Blade para exibir:
                         *
                         * Conforme
                         * Não conforme
                         * Pendente
                         */
                        'status' =>
                        $response
                            ? ($response->result ?? 'pending')
                            : 'pending',

                        /*
                         * Situação final da tratativa.
                         *
                         * Usado para exibir:
                         *
                         * Operacional
                         * Resolvido
                         * Pendente
                         */
                        'final_status' =>
                        $response?->final_status,

                        /*
                         * Entrega para a Blade somente os nomes
                         * dos componentes que apresentaram falha.
                         */
                        'failed_components' =>
                        $this->resolveFailedComponentNames(
                            $response
                        ),
                    ];
                }
            )
            ->values();
    }

    /**
     * Determina a situação da unidade.
     */
    private function resolveUnitStatus(
        Collection $responses,
        bool $completed
    ): string {
        if (! $completed) {
            return 'pending';
        }

        $hasNonConformity = $responses
            ->contains(
                fn($response): bool =>
                $response->result === 'nao_conforme'
            );

        return $hasNonConformity
            ? 'nao_conforme'
            : 'conforme';
    }

    /**
     * Retorna somente os nomes dos componentes
     * que apresentaram falha na resposta.
     */
    private function resolveFailedComponentNames(
        ?PreventiveActivityResponse $response
    ): array {
        if (! $response) {
            return [];
        }

        $responseData = $response->response_data;

        if (! is_array($responseData)) {
            return [];
        }

        return collect($responseData)
            ->map(function ($component): ?array {

                if (is_string($component)) {
                    $component = json_decode(
                        $component,
                        true
                    );
                }

                return is_array($component)
                    ? $component
                    : null;
            })
            ->filter()
            ->filter(
                fn(array $component): bool => ($component['status'] ?? null) === 'failed'
            )
            ->map(
                fn(array $component): ?string =>
                $component['component_name'] ?? null
            )
            ->filter(
                fn(?string $name): bool =>
                is_string($name)
                    && trim($name) !== ''
            )
            ->map(
                fn(string $name): string =>
                trim($name)
            )
            ->unique()
            ->values()
            ->all();
    }

    /**
     * Extrai e normaliza todos os componentes
     * que apresentaram falha.
     *
     * Mantém os dados completos para uso futuro.
     */
   private function collectFailedComponents(
    Collection $responses
): array {
    $components = [];

    foreach ($responses as $response) {

        $responseData = $response->response_data;

        if (! is_array($responseData)) {
            continue;
        }

        foreach ($responseData as $component) {

            if (is_string($component)) {
                $component = json_decode(
                    $component,
                    true
                );
            }

            if (! is_array($component)) {
                continue;
            }

            if (($component['status'] ?? null) !== 'failed') {
                continue;
            }

            $components[] = $component;
        }
    }

    return $components;
}

    /**
     * Normaliza o response_data.
     *
     * O Model normalmente já entrega um array através
     * do cast, mas o método também suporta JSON string.
     */
    private function normalizeResponseData(
        mixed $responseData
    ): array {
        if (is_array($responseData)) {
            return $responseData;
        }

        if (! is_string($responseData)) {
            return [];
        }

        $decoded = json_decode(
            $responseData,
            true
        );

        return is_array($decoded)
            ? $decoded
            : [];
    }

    /**
     * Extrai as observações registradas.
     */
    private function collectObservations(
        Collection $responses
    ): array {
        return $responses
            ->pluck('observation')
            ->filter(
                fn($observation): bool =>
                is_string($observation)
                    && trim($observation) !== ''
            )
            ->map(
                fn(string $observation): string =>
                trim($observation)
            )
            ->values()
            ->all();
    }

    /**
     * Monta o resumo geral de um ciclo.
     */
    private function buildGeneralSummary(
        Collection $units
    ): array {
        $totalUnits =
            $units->count();

        $completedUnits =
            $units
            ->filter(
                fn(array $unit): bool =>
                $unit['progress']['completed']
            )
            ->count();

        $conformingUnits =
            $units
            ->where('status', 'conforme')
            ->count();

        $nonConformingUnits =
            $units
            ->where('status', 'nao_conforme')
            ->count();

        $pendingUnits =
            $units
            ->filter(
                fn(array $unit): bool =>
                ! $unit['progress']['completed']
            )
            ->count();

        $totalActivities =
            $units->sum(
                fn(array $unit): int =>
                $unit['progress']['total']
            );

        $answeredActivities =
            $units->sum(
                fn(array $unit): int =>
                $unit['progress']['answered']
            );

        $pendingActivities =
            $units->sum(
                fn(array $unit): int =>
                $unit['progress']['pending']
            );

        $failedComponents =
            $units
            ->flatMap(
                fn(array $unit): array =>
                $unit['failed_components'] ?? []
            )
            ->values()
            ->all();

        $observations =
            $units
            ->flatMap(
                fn(array $unit): array =>
                $unit['observations'] ?? []
            )
            ->filter(
                fn($observation): bool =>
                is_string($observation)
                    && trim($observation) !== ''
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

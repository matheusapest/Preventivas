<?php

declare(strict_types=1);

namespace App\Services\Preventive;

use App\Enums\PreventiveActivityFinalStatusEnum;
use App\Models\Preventive\Preventive;
use App\Models\Preventive\PreventiveActivityResponse;
use App\Models\Preventive\PreventiveCycle;
use App\Models\Preventive\PreventiveCycleUnit;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class GetPreventiveExecutionDetailsService
{
    /**
     * Retorna os dados necessários para exibir
     * a tela de execução da preventiva.
     *
     * A execução utiliza exclusivamente
     * a estrutura congelada do ciclo atual.
     */
    public function execute(Preventive $preventive): array
    {
        $cycle = $this->getCurrentCycle($preventive);

        $this->loadExecutionRelations($cycle);

        $units = $this->buildUnits($cycle);

        $pendingUnits = $this->getPendingUnits($units);

        $progress = $this->buildGeneralProgress($units);

        $cycles = collect([
            $this->buildCycleSummary(
                $cycle,
                $units
            ),
        ]);

        return [
            'preventive' => $preventive,

            /*
            |--------------------------------------------------------------------------
            | Compatibilidade com a Blade atual
            |--------------------------------------------------------------------------
            */

            'cycle' => $cycle,

            'units' => $units,

            'pendingUnits' => $pendingUnits,

            'progress' => $progress,

            /*
            |--------------------------------------------------------------------------
            | Estrutura padronizada
            |--------------------------------------------------------------------------
            */

            'cycles' => $cycles,
        ];
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
     * Carrega toda a estrutura congelada necessária
     * para a tela de execução.
     */
    private function loadExecutionRelations(
        PreventiveCycle $cycle
    ): void {
        $cycle->load([
            'units.snapshotUnit',
            'units.activities.snapshotRuleActivity',
            'units.activityResponses',
        ]);
    }

    /**
     * Monta o resumo de apresentação do ciclo.
     */
    private function buildCycleSummary(
        PreventiveCycle $cycle,
        Collection $units
    ): array {
        return [
            'cycle' => $cycle,

            'sequence' => $cycle->sequence,

            'units' => $units,

            'summary' => $this->buildGeneralSummary(
                $units
            ),
        ];
    }

    /**
     * Monta todas as unidades do ciclo.
     */
    private function buildUnits(
        PreventiveCycle $cycle
    ): Collection {
        return $cycle->units
            ->map(
                fn (
                    PreventiveCycleUnit $cycleUnit
                ): array => $this->buildUnit(
                    $cycleUnit
                )
            )
            ->values();
    }

    /**
     * Monta os dados de uma unidade.
     */
    private function buildUnit(
        PreventiveCycleUnit $cycleUnit
    ): array {
        $snapshotUnit =
            $cycleUnit->snapshotUnit;

        $activities =
            $this->buildActivities($cycleUnit);

        $totalActivities =
            $activities->count();

        $answeredActivities =
            $activities
                ->where('answered', true)
                ->count();

        $pendingActivities =
            $totalActivities -
            $answeredActivities;

        $completed =
            $totalActivities > 0 &&
            $pendingActivities === 0;

        $responses =
            $activities
                ->pluck('response')
                ->filter();

        /*
        |--------------------------------------------------------------------------
        | Status geral da unidade
        |--------------------------------------------------------------------------
        */

        $unitStatus =
            $this->resolveUnitStatus(
                $responses,
                $completed
            );

        /*
        |--------------------------------------------------------------------------
        | Componentes com falha
        |--------------------------------------------------------------------------
        */

        $failedComponents =
            $this->collectFailedComponents(
                $responses
            );

        /*
        |--------------------------------------------------------------------------
        | Observações
        |--------------------------------------------------------------------------
        */

        $observations =
            $this->collectObservations(
                $responses
            );

        return [
            /*
            |--------------------------------------------------------------------------
            | Cycle Unit
            |--------------------------------------------------------------------------
            */

            'cycle_unit' =>
                $cycleUnit,

            /*
            |--------------------------------------------------------------------------
            | Snapshot
            |--------------------------------------------------------------------------
            */

            'snapshot_unit' =>
                $snapshotUnit,

            /*
            |--------------------------------------------------------------------------
            | Unidade
            |--------------------------------------------------------------------------
            */

            'unit_name' =>
                $snapshotUnit?->operational_unit_name
                ?? 'Unidade operacional',

            'operational_unit_identifier' =>
                $snapshotUnit?->operational_unit_identifier,

            'unit_type_name' =>
                $snapshotUnit?->unit_type_name,

            'operational_profile_name' =>
                $snapshotUnit?->operational_profile_name,

            /*
            |--------------------------------------------------------------------------
            | Status da unidade
            |--------------------------------------------------------------------------
            */

            'status' =>
                $unitStatus,

            /*
            |--------------------------------------------------------------------------
            | Atividades
            |--------------------------------------------------------------------------
            */

            'activities' =>
                $activities,

            /*
            |--------------------------------------------------------------------------
            | Componentes com falha
            |--------------------------------------------------------------------------
            */

            'failed_components' =>
                $failedComponents,

            /*
            |--------------------------------------------------------------------------
            | Observações
            |--------------------------------------------------------------------------
            */

            'observations' =>
                $observations,

            /*
            |--------------------------------------------------------------------------
            | Progresso
            |--------------------------------------------------------------------------
            */

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
     * Monta as atividades da unidade juntamente
     * com suas respectivas respostas.
     */
    private function buildActivities(
        PreventiveCycleUnit $cycleUnit
    ): Collection {
        return $cycleUnit->activities
            ->map(
                function ($cycleUnitActivity) use (
                    $cycleUnit
                ): array {
                    $activity =
                        $cycleUnitActivity
                            ->snapshotRuleActivity;

                    $response =
                        $cycleUnit->activityResponses
                            ->firstWhere(
                                'snapshot_rule_activity_id',
                                $cycleUnitActivity
                                    ->snapshot_rule_activity_id
                            );

                    return [
                        /*
                        |--------------------------------------------------------------------------
                        | Atividade
                        |--------------------------------------------------------------------------
                        */

                        'cycle_unit_activity' =>
                            $cycleUnitActivity,

                        'activity' =>
                            $activity,

                        /*
                        |--------------------------------------------------------------------------
                        | Dados de apresentação da atividade
                        |--------------------------------------------------------------------------
                        */

                        'activity_type' =>
                            $activity?->activity_type,

                        'activity_type_label' =>
                            $this->resolveActivityTypeLabel(
                                $activity?->activity_type
                            ),

                        /*
                        |--------------------------------------------------------------------------
                        | Resposta
                        |--------------------------------------------------------------------------
                        */

                        'response' =>
                            $response,

                        'answered' =>
                            $response !== null,

                        /*
                        |--------------------------------------------------------------------------
                        | Resultado da inspeção
                        |--------------------------------------------------------------------------
                        |
                        | Exemplos:
                        |
                        | conforme
                        | nao_conforme
                        |
                        */

                        'status' =>
                            $response
                                ? (
                                    $response->result
                                    ?? 'pending'
                                )
                                : 'pending',

                        /*
                        |--------------------------------------------------------------------------
                        | Situação final
                        |--------------------------------------------------------------------------
                        |
                        | O Model possui cast para:
                        |
                        | PreventiveActivityFinalStatusEnum
                        |
                        */

                        'final_status' =>
                            $this->resolveActivityFinalStatus(
                                $response
                            ),

                        /*
                        |--------------------------------------------------------------------------
                        | Componentes com falha
                        |--------------------------------------------------------------------------
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
     * Resolve o label do tipo da atividade.
     *
     * O objetivo é impedir que valores internos como:
     *
     * operational_composition
     *
     * sejam enviados diretamente para a Blade.
     *
     * Quando o atributo já estiver convertido para um Enum
     * que possua o método label(), o próprio Enum será utilizado.
     */
    private function resolveActivityTypeLabel(
        mixed $activityType
    ): string {
        if ($activityType === null) {
            return '—';
        }

        /*
        |--------------------------------------------------------------------------
        | Enum com método label()
        |--------------------------------------------------------------------------
        */

        if (
            is_object($activityType)
            && method_exists($activityType, 'label')
        ) {
            return (string) $activityType->label();
        }

        /*
        |--------------------------------------------------------------------------
        | Valor string
        |--------------------------------------------------------------------------
        |
        | Compatibilidade enquanto o cast do Model não estiver
        | disponível ou para registros antigos.
        |--------------------------------------------------------------------------
        */

        if (is_string($activityType)) {
            return match ($activityType) {
                'operational_composition' =>
                    'Composição operacional',

                default =>
                    $activityType,
            };
        }

        return '—';
    }

    /**
     * Retorna a situação final da atividade.
     *
     * O Model já possui cast para o Enum.
     */
    private function resolveActivityFinalStatus(
        ?PreventiveActivityResponse $response
    ): ?PreventiveActivityFinalStatusEnum {
        if (! $response) {
            return null;
        }

        $finalStatus =
            $response->final_status;

        /*
        |--------------------------------------------------------------------------
        | Já veio como Enum
        |--------------------------------------------------------------------------
        */

        if (
            $finalStatus
            instanceof PreventiveActivityFinalStatusEnum
        ) {
            return $finalStatus;
        }

        /*
        |--------------------------------------------------------------------------
        | Compatibilidade com registros antigos
        |--------------------------------------------------------------------------
        */

        if (is_string($finalStatus)) {
            return PreventiveActivityFinalStatusEnum::tryFrom(
                $finalStatus
            );
        }

        return null;
    }

    /**
     * Resolve o status geral da unidade.
     *
     * O status da unidade é baseado no resultado
     * das atividades:
     *
     * - pending
     * - conforme
     * - nao_conforme
     */
    private function resolveUnitStatus(
        Collection $responses,
        bool $completed
    ): string {
        /*
        |--------------------------------------------------------------------------
        | Nenhuma atividade respondida
        |--------------------------------------------------------------------------
        */

        if ($responses->isEmpty()) {
            return 'pending';
        }

        /*
        |--------------------------------------------------------------------------
        | Ainda existem atividades pendentes
        |--------------------------------------------------------------------------
        */

        if (! $completed) {
            return 'pending';
        }

        /*
        |--------------------------------------------------------------------------
        | Unidade concluída
        |--------------------------------------------------------------------------
        |
        | Se qualquer atividade foi não conforme,
        | a unidade inteira é considerada não conforme.
        |
        */

        if (
            $responses->contains(
                fn (
                    PreventiveActivityResponse $response
                ): bool =>
                    $response->result === 'nao_conforme'
            )
        ) {
            return 'nao_conforme';
        }

        /*
        |--------------------------------------------------------------------------
        | Todas as atividades foram concluídas
        |--------------------------------------------------------------------------
        */

        return 'conforme';
    }

    /**
     * Retorna somente os nomes dos componentes
     * que apresentaram falha.
     */
    private function resolveFailedComponentNames(
        ?PreventiveActivityResponse $response
    ): array {
        if (! $response) {
            return [];
        }

        $responseData =
            $this->normalizeResponseData(
                $response->response_data
            );

        return collect($responseData)
            ->map(
                function ($component): ?array {
                    /*
                    |--------------------------------------------------------------------------
                    | Alguns registros podem estar armazenados
                    | como JSON dentro do próprio array.
                    |--------------------------------------------------------------------------
                    */

                    if (is_string($component)) {
                        $component =
                            json_decode(
                                $component,
                                true
                            );
                    }

                    return is_array($component)
                        ? $component
                        : null;
                }
            )
            ->filter()
            ->filter(
                fn (array $component): bool =>
                    ($component['status'] ?? null)
                    === 'failed'
            )
            ->map(
                fn (array $component): ?string =>
                    $component['component_name']
                    ?? null
            )
            ->filter(
                fn (?string $name): bool =>
                    is_string($name)
                    && trim($name) !== ''
            )
            ->map(
                fn (string $name): string =>
                    trim($name)
            )
            ->unique()
            ->values()
            ->all();
    }

    /**
     * Extrai e normaliza todos os componentes
     * que apresentaram falha.
     */
    private function collectFailedComponents(
        Collection $responses
    ): array {
        $components = [];

        foreach ($responses as $response) {
            $responseData =
                $this->normalizeResponseData(
                    $response->response_data
                );

            foreach ($responseData as $component) {
                if (is_string($component)) {
                    $component =
                        json_decode(
                            $component,
                            true
                        );
                }

                if (! is_array($component)) {
                    continue;
                }

                if (
                    ($component['status'] ?? null)
                    !== 'failed'
                ) {
                    continue;
                }

                $components[] =
                    $component;
            }
        }

        return $components;
    }

    /**
     * Normaliza o response_data.
     *
     * Suporta:
     *
     * - array já convertido pelo cast;
     * - JSON armazenado como string.
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

        $decoded =
            json_decode(
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
                fn ($observation): bool =>
                    is_string($observation)
                    && trim($observation) !== ''
            )
            ->map(
                fn (string $observation): string =>
                    trim($observation)
            )
            ->values()
            ->all();
    }

    /**
     * Retorna somente unidades que ainda possuem
     * atividades pendentes.
     */
    private function getPendingUnits(
        Collection $units
    ): Collection {
        return $units
            ->filter(
                fn (array $unit): bool =>
                    $unit['progress']['pending'] > 0
            )
            ->values();
    }

    /**
     * Calcula o progresso geral da preventiva.
     */
    private function buildGeneralProgress(
        Collection $units
    ): array {
        $totalActivities =
            $units->sum(
                fn (array $unit): int =>
                    $unit['progress']['total']
            );

        $answeredActivities =
            $units->sum(
                fn (array $unit): int =>
                    $unit['progress']['answered']
            );

        $pendingActivities =
            $units->sum(
                fn (array $unit): int =>
                    $unit['progress']['pending']
            );

        return [
            'total_activities' =>
                $totalActivities,

            'answered_activities' =>
                $answeredActivities,

            'pending_activities' =>
                $pendingActivities,

            'completed' =>
                $totalActivities > 0
                && $pendingActivities === 0,
        ];
    }

    /**
     * Monta o resumo geral do ciclo.
     */
    private function buildGeneralSummary(
        Collection $units
    ): array {
        $totalUnits =
            $units->count();

        $completedUnits =
            $units
                ->filter(
                    fn (array $unit): bool =>
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
                    fn (array $unit): bool =>
                        ! $unit['progress']['completed']
                )
                ->count();

        $totalActivities =
            $units->sum(
                fn (array $unit): int =>
                    $unit['progress']['total']
            );

        $answeredActivities =
            $units->sum(
                fn (array $unit): int =>
                    $unit['progress']['answered']
            );

        $pendingActivities =
            $units->sum(
                fn (array $unit): int =>
                    $unit['progress']['pending']
            );

        $failedComponents =
            $units
                ->flatMap(
                    fn (array $unit): array =>
                        $unit['failed_components'] ?? []
                )
                ->values()
                ->all();

        $observations =
            $units
                ->flatMap(
                    fn (array $unit): array =>
                        $unit['observations'] ?? []
                )
                ->filter(
                    fn ($observation): bool =>
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
                $totalActivities > 0
                && $pendingActivities === 0,

            'failed_components' =>
                $failedComponents,

            'observations' =>
                $observations,
        ];
    }
}

<?php

declare(strict_types=1);

namespace App\Services\Preventive\Execution;

use App\Enums\ActivityKind;
use App\Enums\StatusCycleEnum;
use App\Enums\StatusPreventiveEnum;
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
     * a tela de execução/consulta da preventiva.
     *
     * A tela pode ser acessada mesmo quando o ciclo
     * já estiver finalizado.
     *
     * A possibilidade de executar novas atividades
     * é determinada separadamente através de
     * "can_execute".
     */
    public function execute(Preventive $preventive): array
    {
        /*
         * Localiza sempre o ciclo atual.
         *
         * Não bloqueamos a consulta caso o ciclo
         * esteja finalizado.
         */
        $cycle = $this->getCurrentCycle($preventive);

        /*
         * Carrega a estrutura congelada do ciclo.
         *
         * Isso permite consultar inclusive ciclos
         * já finalizados.
         */
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

        /*
         * Define se o técnico ainda pode executar
         * atividades neste ciclo.
         */
        $canExecute =
            $this->canExecute(
                $preventive,
                $cycle
            );

        /*
         * Define o motivo apresentado pela interface
         * quando a execução estiver bloqueada.
         */
        $executionLockedReason =
            $this->resolveExecutionLockedReason(
                $preventive,
                $cycle,
                $canExecute
            );

        return [
            /*
            |--------------------------------------------------------------------------
            | Preventiva
            |--------------------------------------------------------------------------
            */

            'preventive' =>
                $preventive,

            /*
            |--------------------------------------------------------------------------
            | Ciclo atual
            |--------------------------------------------------------------------------
            */

            'cycle' =>
                $cycle,

            /*
            |--------------------------------------------------------------------------
            | Unidades
            |--------------------------------------------------------------------------
            */

            'units' =>
                $units,

            /*
            |--------------------------------------------------------------------------
            | Unidades pendentes
            |--------------------------------------------------------------------------
            */

            'pendingUnits' =>
                $pendingUnits,

            /*
            |--------------------------------------------------------------------------
            | Progresso
            |--------------------------------------------------------------------------
            */

            'progress' =>
                $progress,

            /*
            |--------------------------------------------------------------------------
            | Ciclos
            |--------------------------------------------------------------------------
            */

            'cycles' =>
                $cycles,

            /*
            |--------------------------------------------------------------------------
            | Permissão de execução
            |--------------------------------------------------------------------------
            |
            | Importante:
            |
            | Este valor controla somente a possibilidade
            | de executar novas atividades.
            |
            | Ele NÃO impede a visualização do ciclo.
            |
            */

            'can_execute' =>
                $canExecute,

            /*
            |--------------------------------------------------------------------------
            | Motivo do bloqueio
            |--------------------------------------------------------------------------
            */

            'execution_locked_reason' =>
                $executionLockedReason,
        ];
    }

    /**
     * Localiza o ciclo atual da preventiva.
     *
     * O ciclo atual continua sendo carregado mesmo
     * quando estiver finalizado, pois a tela também
     * possui função de consulta/histórico.
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
     * Determina se o técnico pode executar novas
     * atividades no ciclo atual.
     *
     * Esta regra NÃO controla a visualização.
     *
     * Um ciclo finalizado continua podendo ser consultado,
     * porém nunca pode receber novas respostas.
     */
    private function canExecute(
        Preventive $preventive,
        PreventiveCycle $cycle
    ): bool {
        /*
        |--------------------------------------------------------------------------
        | Status da Preventiva
        |--------------------------------------------------------------------------
        |
        | Somente preventivas novas ou em execução
        | podem receber novas respostas.
        |
        */

        if (
            ! in_array(
                $preventive->status,
                [
                    StatusPreventiveEnum::NEW,
                    StatusPreventiveEnum::IN_PROGRESS,
                ],
                true
            )
        ) {
            return false;
        }

        /*
        |--------------------------------------------------------------------------
        | Status do Cycle
        |--------------------------------------------------------------------------
        |
        | Um ciclo FINISHED é imutável.
        |
        | Caso a preventiva esteja em IN_PROGRESS e o
        | ciclo atual esteja FINISHED, significa que
        | normalmente estamos aguardando o gestor criar
        | o próximo ciclo.
        |
        */

        if (
            $cycle->status ===
            StatusCycleEnum::FINISHED
        ) {
            return false;
        }

        return true;
    }

    /**
     * Resolve a mensagem apresentada quando
     * novas atividades não podem ser executadas.
     */
    private function resolveExecutionLockedReason(
        Preventive $preventive,
        PreventiveCycle $cycle,
        bool $canExecute
    ): ?string {
        if ($canExecute) {
            return null;
        }

        /*
        |--------------------------------------------------------------------------
        | Preventiva reprovada / aguardando novo ciclo
        |--------------------------------------------------------------------------
        |
        | A Preventiva permanece IN_PROGRESS após uma
        | reprovação, enquanto o ciclo anterior fica
        | FINISHED.
        |
        */

        if (
            $preventive->status ===
            StatusPreventiveEnum::IN_PROGRESS
            &&
            $cycle->status ===
            StatusCycleEnum::FINISHED
        ) {
            return
                'A preventiva foi reprovada e aguarda a criação de um novo ciclo pelo gestor.';
        }

        /*
        |--------------------------------------------------------------------------
        | Aguardando aprovação
        |--------------------------------------------------------------------------
        */

        if (
            $preventive->status ===
            StatusPreventiveEnum::PENDING_APPROVAL
        ) {
            return
                'A preventiva está aguardando a validação do gestor.';
        }

        /*
        |--------------------------------------------------------------------------
        | Aprovada
        |--------------------------------------------------------------------------
        */

        if (
            $preventive->status ===
            StatusPreventiveEnum::APPROVED
        ) {
            return
                'A preventiva foi aprovada e não possui novas atividades para execução.';
        }

        /*
        |--------------------------------------------------------------------------
        | Demais situações
        |--------------------------------------------------------------------------
        */

        return
            'Esta preventiva não está disponível para novas atividades.';
    }

    /**
     * Carrega toda a estrutura congelada necessária
     * para a tela de execução/consulta.
     */
    private function loadExecutionRelations(
        PreventiveCycle $cycle
    ): void {
        $cycle->load([
            'units.snapshotUnit',
            'units.activities.snapshotRuleActivity',
            'units.activityResponses.photo',
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
            'cycle' =>
                $cycle,

            'sequence' =>
                $cycle->sequence,

            'units' =>
                $units,

            'summary' =>
                $this->buildGeneralSummary(
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
                ): array =>
                    $this->buildUnit(
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
            $this->buildActivities(
                $cycleUnit
            );

        $totalActivities =
            $activities->count();

        $answeredActivities =
            $activities
                ->where(
                    'answered',
                    true
                )
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
                $activities,
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
     *
     * Cada tipo de atividade possui seu próprio
     * contrato de apresentação.
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
                        $cycleUnit
                            ->activityResponses
                            ->firstWhere(
                                'snapshot_rule_activity_id',
                                $cycleUnitActivity
                                    ->snapshot_rule_activity_id
                            );

                    $activityType =
                        $this->resolveActivityKind(
                            $activity?->activity_type
                        );

                    /*
                    |--------------------------------------------------------------------------
                    | Dados comuns
                    |--------------------------------------------------------------------------
                    */

                    $data = [
                        'cycle_unit_activity' =>
                            $cycleUnitActivity,

                        'activity' =>
                            $activity,

                        'activity_type' =>
                            $activityType?->value,

                        'activity_type_label' =>
                            $this->resolveActivityTypeLabel(
                                $activityType
                            ),

                        'response' =>
                            $response,

                        'answered' =>
                            $response !== null,

                        /*
                        |--------------------------------------------------------------------------
                        | Resultado
                        |--------------------------------------------------------------------------
                        */

                        'result' =>
                            null,

                        'result_label' =>
                            null,

                        /*
                        |--------------------------------------------------------------------------
                        | Situação final
                        |--------------------------------------------------------------------------
                        */

                        'final_status' =>
                            null,

                        'final_status_label' =>
                            null,

                        /*
                        |--------------------------------------------------------------------------
                        | Dados específicos
                        |--------------------------------------------------------------------------
                        */

                        'failed_components' =>
                            [],

                        'photo' =>
                            null,

                        'response_data' =>
                            $response?->response_data,

                        'observation' =>
                            $response?->observation,
                    ];

                    /*
                    |--------------------------------------------------------------------------
                    | Tipo inválido ou inexistente
                    |--------------------------------------------------------------------------
                    */

                    if (! $activityType) {
                        return $data;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Contrato específico de cada atividade
                    |--------------------------------------------------------------------------
                    */

                    return match ($activityType) {
                        ActivityKind::OPERATIONAL_COMPOSITION =>
                            array_merge(
                                $data,
                                $this->buildOperationalCompositionData(
                                    $response
                                )
                            ),

                        ActivityKind::PHOTO =>
                            array_merge(
                                $data,
                                $this->buildPhotoData(
                                    $response
                                )
                            ),

                        ActivityKind::TEXT,
                        ActivityKind::NUMBER,
                        ActivityKind::BOOLEAN =>
                            $this->buildGenericActivityData(
                                $data,
                                $response
                            ),
                    };
                }
            )
            ->values();
    }

    /**
     * Resolve o ActivityKind.
     */
    private function resolveActivityKind(
        mixed $activityType
    ): ?ActivityKind {
        if (
            $activityType
            instanceof ActivityKind
        ) {
            return $activityType;
        }

        if (! is_string($activityType)) {
            return null;
        }

        return ActivityKind::tryFrom(
            $activityType
        );
    }

    /**
     * Resolve o label amigável do tipo da atividade.
     *
     * O Enum é a fonte oficial da apresentação.
     */
    private function resolveActivityTypeLabel(
        ?ActivityKind $activityType
    ): string {
        if (! $activityType) {
            return '—';
        }

        return $activityType->label();
    }

    /**
     * Monta os dados específicos da composição operacional.
     *
     * Somente este tipo trabalha com:
     *
     * - result
     * - final_status
     * - failed_components
     */
    private function buildOperationalCompositionData(
        ?PreventiveActivityResponse $response
    ): array {
        if (! $response) {
            return [
                'result' =>
                    null,

                'result_label' =>
                    null,

                'final_status' =>
                    null,

                'final_status_label' =>
                    null,

                'failed_components' =>
                    [],
            ];
        }

        $result =
            $response->result;

        $finalStatus =
            $this->resolveActivityFinalStatus(
                $response
            );

        return [
            'result' =>
                $result,

            'result_label' =>
                $this->resolveResultLabel(
                    $result
                ),

            'final_status' =>
                $finalStatus,

            'final_status_label' =>
                $this->resolveActivityFinalStatusLabel(
                    $finalStatus
                ),

            'failed_components' =>
                $this->resolveFailedComponentNames(
                    $response
                ),
        ];
    }

    /**
     * Monta os dados específicos de uma atividade fotográfica.
     *
     * PHOTO não possui:
     *
     * - result
     * - final_status
     *
     * Sua resposta é composta pela evidência fotográfica
     * e pela observação.
     */
    private function buildPhotoData(
        ?PreventiveActivityResponse $response
    ): array {
        if (! $response) {
            return [
                'photo' =>
                    null,

                'observation' =>
                    null,
            ];
        }

        return [
            'photo' =>
                $response->photo,

            'observation' =>
                $response->observation,
        ];
    }

    /**
     * Monta os dados das atividades genéricas.
     *
     * TEXT, NUMBER e BOOLEAN não possuem
     * avaliação operacional.
     */
    private function buildGenericActivityData(
        array $data,
        ?PreventiveActivityResponse $response
    ): array {
        return array_merge(
            $data,
            [
                'response_data' =>
                    $response?->response_data,

                'observation' =>
                    $response?->observation,
            ]
        );
    }

    /**
     * Resolve o label amigável do resultado.
     */
    private function resolveResultLabel(
        ?string $result
    ): ?string {
        return match ($result) {
            'conforme' =>
                'Conforme',

            'nao_conforme' =>
                'Não conforme',

            default =>
                null,
        };
    }

    /**
     * Retorna a situação final da atividade.
     *
     * O Model possui cast para o Enum.
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
     * Resolve o label da situação final.
     */
    private function resolveActivityFinalStatusLabel(
        ?PreventiveActivityFinalStatusEnum $finalStatus
    ): ?string {
        if (! $finalStatus) {
            return null;
        }

        return $finalStatus->label();
    }

    /**
     * Resolve o status geral da unidade.
     *
     * Somente atividades que possuem resultado
     * participam da avaliação de conformidade.
     *
     * Atividades como PHOTO não possuem result
     * e portanto não podem, sozinhas, marcar uma
     * unidade como não conforme.
     */
    private function resolveUnitStatus(
        Collection $activities,
        bool $completed
    ): string {
        /*
        |--------------------------------------------------------------------------
        | Nenhuma atividade
        |--------------------------------------------------------------------------
        */

        if ($activities->isEmpty()) {
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
        | Considera somente atividades avaliativas
        |--------------------------------------------------------------------------
        */

        $evaluatedActivities =
            $activities->filter(
                fn (
                    array $activity
                ): bool =>
                    $activity['result'] !== null
            );

        /*
        |--------------------------------------------------------------------------
        | Não existe resultado operacional
        |--------------------------------------------------------------------------
        |
        | Exemplo:
        |
        | Unidade com apenas atividade PHOTO.
        |
        | A unidade está concluída, mas não foi
        | submetida a uma avaliação de conformidade.
        |
        */

        if ($evaluatedActivities->isEmpty()) {
            return 'conforme';
        }

        /*
        |--------------------------------------------------------------------------
        | Existe alguma atividade não conforme
        |--------------------------------------------------------------------------
        */

        if (
            $evaluatedActivities->contains(
                fn (
                    array $activity
                ): bool =>
                    $activity['result'] ===
                    'nao_conforme'
            )
        ) {
            return 'nao_conforme';
        }

        /*
        |--------------------------------------------------------------------------
        | Todas as atividades avaliativas estão conformes
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
                fn (
                    array $component
                ): bool =>
                    ($component['status'] ?? null)
                    === 'failed'
            )
            ->map(
                fn (
                    array $component
                ): ?string =>
                    $component['component_name']
                    ?? null
            )
            ->filter(
                fn (
                    ?string $name
                ): bool =>
                    is_string($name)
                    && trim($name) !== ''
            )
            ->map(
                fn (
                    string $name
                ): string =>
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
     * - JSON armazenado como string;
     * - array contendo JSON strings.
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
                fn (
                    $observation
                ): bool =>
                    is_string($observation)
                    && trim($observation) !== ''
            )
            ->map(
                fn (
                    string $observation
                ): string =>
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
                fn (
                    array $unit
                ): bool =>
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
                fn (
                    array $unit
                ): int =>
                    $unit['progress']['total']
            );

        $answeredActivities =
            $units->sum(
                fn (
                    array $unit
                ): int =>
                    $unit['progress']['answered']
            );

        $pendingActivities =
            $units->sum(
                fn (
                    array $unit
                ): int =>
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
                    fn (
                        array $unit
                    ): bool =>
                        $unit['progress']['completed']
                )
                ->count();

        $conformingUnits =
            $units
                ->where(
                    'status',
                    'conforme'
                )
                ->count();

        $nonConformingUnits =
            $units
                ->where(
                    'status',
                    'nao_conforme'
                )
                ->count();

        $pendingUnits =
            $units
                ->filter(
                    fn (
                        array $unit
                    ): bool =>
                        ! $unit['progress']['completed']
                )
                ->count();

        $totalActivities =
            $units->sum(
                fn (
                    array $unit
                ): int =>
                    $unit['progress']['total']
            );

        $answeredActivities =
            $units->sum(
                fn (
                    array $unit
                ): int =>
                    $unit['progress']['answered']
            );

        $pendingActivities =
            $units->sum(
                fn (
                    array $unit
                ): int =>
                    $unit['progress']['pending']
            );

        $failedComponents =
            $units
                ->flatMap(
                    fn (
                        array $unit
                    ): array =>
                        $unit['failed_components']
                        ?? []
                )
                ->values()
                ->all();

        $observations =
            $units
                ->flatMap(
                    fn (
                        array $unit
                    ): array =>
                        $unit['observations']
                        ?? []
                )
                ->filter(
                    fn (
                        $observation
                    ): bool =>
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

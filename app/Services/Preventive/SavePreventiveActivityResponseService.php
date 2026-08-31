<?php

declare(strict_types=1);

namespace App\Services\Preventive;

use App\Enums\ActivityKind;
use App\Enums\PreventiveActivityFinalStatusEnum;
use App\Enums\StatusPreventiveEnum;
use App\Models\Preventive;
use App\Models\PreventiveActivityResponse;
use App\Models\PreventiveCycleUnit;
use App\Models\PreventiveCycleUnitActivity;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class SavePreventiveActivityResponseService
{
    public function __construct(
        private readonly FinalizePreventiveService $finalizePreventiveService,
        private readonly PreventiveActivityPhotoService $photoService,
    ) {}

    /**
     * Persiste a resposta de uma atividade da unidade
     * dentro do ciclo atual da preventiva.
     *
     * O Service atua como orquestrador das respostas.
     *
     * A PreventiveActivityResponse é a entidade central
     * da execução da atividade.
     *
     * Cada tipo de atividade possui seu próprio tratamento,
     * mas apenas a composição operacional trabalha com
     * result e final_status.
     */
    public function execute(
        PreventiveCycleUnit $cycleUnit,
        int $snapshotRuleActivityId,
        array $data,
    ): PreventiveActivityResponse {
        /*
         * Garante que a atividade pertence à unidade
         * selecionada dentro do ciclo atual.
         */
        $cycleUnitActivity = $this->findCycleUnitActivity(
            $cycleUnit,
            $snapshotRuleActivityId
        );

        $activityType = $cycleUnitActivity
            ->snapshotRuleActivity
            ?->activity_type;

        if (! $activityType) {
            throw ValidationException::withMessages([
                'activity' =>
                    'O tipo da atividade não foi encontrado no snapshot.',
            ]);
        }

        /*
         * Converte o tipo armazenado no snapshot para
         * o Enum central das atividades.
         */
        try {
            $activityKind = ActivityKind::from(
                $activityType
            );
        } catch (\ValueError) {
            throw ValidationException::withMessages([
                'activity' =>
                    "O tipo de atividade '{$activityType}' é inválido.",
            ]);
        }

        /*
         * Cada tipo possui seu próprio tratamento.
         */
        $response = match ($activityKind) {
            ActivityKind::OPERATIONAL_COMPOSITION =>
                $this->saveOperationalComposition(
                    cycleUnit: $cycleUnit,
                    snapshotRuleActivityId: $snapshotRuleActivityId,
                    data: $data,
                ),

            ActivityKind::PHOTO =>
                $this->savePhoto(
                    cycleUnit: $cycleUnit,
                    snapshotRuleActivityId: $snapshotRuleActivityId,
                    data: $data,
                ),

            ActivityKind::TEXT,
            ActivityKind::NUMBER,
            ActivityKind::BOOLEAN =>
                $this->saveGenericResponse(
                    cycleUnit: $cycleUnit,
                    snapshotRuleActivityId: $snapshotRuleActivityId,
                    data: $data,
                ),
        };

        /*
         * Obtém a preventiva relacionada ao ciclo.
         */
        $preventive = $this->getPreventive(
            $cycleUnit
        );

        if (! $preventive) {
            return $response;
        }

        /*
         * Caso seja a primeira resposta,
         * coloca a preventiva em execução.
         */
        $this->startPreventiveIfNecessary(
            $preventive
        );

        /*
         * Caso todas as atividades tenham sido respondidas,
         * encaminha a preventiva para aprovação.
         */
        $this->finalizeIfCompleted(
            $preventive
        );

        return $response;
    }

    /**
     * Persiste uma resposta de composição operacional.
     *
     * Este fluxo permanece responsável exclusivamente
     * pelas regras operacionais já validadas.
     */
    private function saveOperationalComposition(
        PreventiveCycleUnit $cycleUnit,
        int $snapshotRuleActivityId,
        array $data,
    ): PreventiveActivityResponse {
        $operationalStatus =
            $data['operational_status'] ?? null;

        $finalStatus =
            $data['final_status'] ?? null;

        $observation =
            $data['observation'] ?? null;

        $failedComponents =
            $data['failed_components'] ?? null;

        if (! is_string($operationalStatus)) {
            throw ValidationException::withMessages([
                'operational_status' =>
                    'A situação operacional é obrigatória.',
            ]);
        }

        $this->validateOperationalStatus(
            $operationalStatus
        );

        $result = $this->resolveResult(
            $operationalStatus
        );

        $this->validateFinalStatus(
            $result,
            $finalStatus,
            $observation
        );

        $finalStatusEnum = $this->resolveFinalStatus(
            $result,
            $finalStatus
        );

        $failedComponents = $this->resolveFailedComponents(
            $operationalStatus,
            $failedComponents
        );

        $response = $this->findOrCreateResponse(
            $cycleUnit,
            $snapshotRuleActivityId
        );

        $this->fillResponse(
            $response,
            $result,
            $finalStatusEnum,
            $observation,
            $failedComponents
        );

        $response->save();

        return $response;
    }

    /**
     * Persiste respostas genéricas de atividades.
     *
     * Abrange atualmente:
     *
     * - text
     * - number
     * - boolean
     *
     * Essas atividades não possuem avaliação operacional.
     * Portanto:
     *
     * - result = null
     * - final_status = null
     *
     * A resposta específica é armazenada em response_data.
     */
    private function saveGenericResponse(
        PreventiveCycleUnit $cycleUnit,
        int $snapshotRuleActivityId,
        array $data,
    ): PreventiveActivityResponse {
        if (! array_key_exists('response', $data)) {
            throw ValidationException::withMessages([
                'response' =>
                    'A resposta da atividade não foi informada.',
            ]);
        }

        $responseData = $data['response'];

        $observation =
            $data['observation'] ?? null;

        if ($observation !== null) {
            $observation = trim(
                (string) $observation
            );

            if ($observation === '') {
                $observation = null;
            }
        }

        $response = $this->findOrCreateResponse(
            $cycleUnit,
            $snapshotRuleActivityId
        );

        /*
         * Atividades genéricas não possuem avaliação
         * operacional.
         */
        $response->result = null;

        $response->final_status = null;

        /*
         * A resposta específica da atividade fica
         * centralizada em response_data.
         */
        $response->response_data =
            $responseData;

        $response->observation =
            $observation;

        $response->answered_at = now();

        $response->save();

        return $response;
    }

    /**
     * Persiste uma resposta do tipo PHOTO.
     *
     * A resposta continua sendo o registro central.
     *
     * A foto é processada pelo
     * PreventiveActivityPhotoService.
     *
     * Os metadados são armazenados em
     * PreventiveActivityResponsePhoto.
     *
     * PHOTO não possui result nem final_status.
     */
    private function savePhoto(
        PreventiveCycleUnit $cycleUnit,
        int $snapshotRuleActivityId,
        array $data,
    ): PreventiveActivityResponse {
        $photo = $data['photo'] ?? null;

        if (! $photo instanceof UploadedFile) {
            throw ValidationException::withMessages([
                'photo' =>
                    'A foto é obrigatória.',
            ]);
        }

        $observation =
            $data['observation'] ?? null;

        if ($observation !== null) {
            $observation = trim(
                (string) $observation
            );

            if ($observation === '') {
                $observation = null;
            }
        }

        /*
         * O arquivo é salvo fisicamente antes da transação
         * de banco, porque o Storage não participa da
         * transação SQL.
         *
         * Caso alguma etapa posterior falhe, o arquivo
         * será removido no catch.
         */
        $photoMetadata = null;

        try {
            $photoMetadata = $this->photoService->store(
                $photo
            );

            $response = DB::transaction(
                function () use (
                    $cycleUnit,
                    $snapshotRuleActivityId,
                    $observation,
                    $photoMetadata
                ): PreventiveActivityResponse {
                    /*
                     * Localiza ou cria a resposta central.
                     */
                    $response = $this->findOrCreateResponse(
                        $cycleUnit,
                        $snapshotRuleActivityId
                    );

                    /*
                     * PHOTO não possui avaliação operacional.
                     */
                    $response->result = null;

                    $response->final_status = null;

                    /*
                     * Não existe uma resposta textual
                     * genérica para a atividade PHOTO.
                     *
                     * A evidência principal está no registro
                     * PreventiveActivityResponsePhoto.
                     */
                    $response->response_data = null;

                    /*
                     * A descrição pertence à resposta,
                     * e não aos metadados do arquivo.
                     */
                    $response->observation =
                        $observation;

                    $response->answered_at = now();

                    $response->save();

                    /*
                     * A resposta possui um relacionamento
                     * com a foto.
                     */
                    $response->photo()->updateOrCreate(
                        [],
                        [
                            'path' =>
                                $photoMetadata['path'],

                            'mime_type' =>
                                $photoMetadata['mime_type'],

                            'size' =>
                                $photoMetadata['size'],

                            'captured_at' =>
                                $photoMetadata['captured_at'],
                        ]
                    );

                    return $response;
                }
            );

            return $response;
        } catch (\Throwable $exception) {
            /*
             * Se o arquivo foi criado e alguma operação
             * posterior falhou, remove o arquivo para
             * evitar arquivo órfão no Storage.
             */
            if (
                is_array($photoMetadata) &&
                isset($photoMetadata['path'])
            ) {
                $disk = Storage::disk('local');

                if ($disk->exists(
                    $photoMetadata['path']
                )) {
                    $disk->delete(
                        $photoMetadata['path']
                    );
                }
            }

            throw $exception;
        }
    }

    /**
     * Localiza a atividade dentro da unidade do ciclo.
     *
     * A atividade utilizada na execução é sempre
     * a atividade congelada no snapshot.
     */
    private function findCycleUnitActivity(
        PreventiveCycleUnit $cycleUnit,
        int $snapshotRuleActivityId
    ): PreventiveCycleUnitActivity {
        $cycleUnitActivity = PreventiveCycleUnitActivity::query()
            ->where(
                'preventive_cycle_unit_id',
                $cycleUnit->id
            )
            ->where(
                'snapshot_rule_activity_id',
                $snapshotRuleActivityId
            )
            ->with('snapshotRuleActivity')
            ->first();

        if (! $cycleUnitActivity) {
            throw ValidationException::withMessages([
                'activity' =>
                    'A atividade não pertence à unidade selecionada.',
            ]);
        }

        return $cycleUnitActivity;
    }

    /**
     * Valida a situação operacional enviada pelo formulário.
     */
    private function validateOperationalStatus(
        string $operationalStatus
    ): void {
        if (! in_array(
            $operationalStatus,
            ['yes', 'no'],
            true
        )) {
            throw ValidationException::withMessages([
                'operational_status' =>
                    'A situação operacional informada é inválida.',
            ]);
        }
    }

    /**
     * Converte a resposta da interface para o resultado
     * utilizado no domínio da resposta.
     */
    private function resolveResult(
        string $operationalStatus
    ): string {
        return match ($operationalStatus) {
            'yes' => 'conforme',
            'no' => 'nao_conforme',
        };
    }

    /**
     * Valida a situação final da atividade.
     */
    private function validateFinalStatus(
        string $result,
        ?string $finalStatus,
        ?string $observation
    ): void {
        if ($result === 'conforme') {
            return;
        }

        if (! in_array(
            $finalStatus,
            ['resolvido', 'pendente'],
            true
        )) {
            throw ValidationException::withMessages([
                'final_status' =>
                    'Informe se a não conformidade foi resolvida ou permanece pendente.',
            ]);
        }

        if (
            ! is_string($observation) ||
            trim($observation) === ''
        ) {
            $message = match ($finalStatus) {
                'resolvido' =>
                    'Descreva como a não conformidade foi resolvida.',

                'pendente' =>
                    'Descreva por que a não conformidade permanece pendente.',

                default =>
                    'Descreva a situação da não conformidade.',
            };

            throw ValidationException::withMessages([
                'observation' => $message,
            ]);
        }
    }

    /**
     * Define a situação final da atividade.
     */
    private function resolveFinalStatus(
        string $result,
        ?string $finalStatus
    ): PreventiveActivityFinalStatusEnum {
        if ($result === 'conforme') {
            return PreventiveActivityFinalStatusEnum::OPERATIONAL;
        }

        return match ($finalStatus) {
            'resolvido' =>
                PreventiveActivityFinalStatusEnum::RESOLVED,

            'pendente' =>
                PreventiveActivityFinalStatusEnum::PENDING,

            default =>
                throw ValidationException::withMessages([
                    'final_status' =>
                        'A situação final informada é inválida.',
                ]),
        };
    }

    /**
     * Remove componentes com falha quando o conjunto
     * foi informado como operacional.
     */
    private function resolveFailedComponents(
        string $operationalStatus,
        ?array $failedComponents
    ): ?array {
        if ($operationalStatus === 'yes') {
            return null;
        }

        return $failedComponents;
    }

    /**
     * Localiza uma resposta existente ou cria uma nova.
     *
     * Uma unidade + atividade possui uma única resposta.
     */
    private function findOrCreateResponse(
        PreventiveCycleUnit $cycleUnit,
        int $snapshotRuleActivityId
    ): PreventiveActivityResponse {
        $response = PreventiveActivityResponse::query()
            ->where(
                'preventive_cycle_unit_id',
                $cycleUnit->id
            )
            ->where(
                'snapshot_rule_activity_id',
                $snapshotRuleActivityId
            )
            ->first();

        if ($response) {
            return $response;
        }

        $response = new PreventiveActivityResponse();

        $response->preventive_cycle_unit_id =
            $cycleUnit->id;

        $response->snapshot_rule_activity_id =
            $snapshotRuleActivityId;

        $response->started_at = now();

        return $response;
    }

    /**
     * Preenche os dados da resposta operacional.
     */
    private function fillResponse(
        PreventiveActivityResponse $response,
        string $result,
        PreventiveActivityFinalStatusEnum $finalStatus,
        ?string $observation,
        ?array $failedComponents
    ): void {
        $response->result = $result;

        $response->final_status = $finalStatus;

        $response->observation =
            $observation !== null
                ? trim($observation)
                : null;

        $response->response_data =
            $failedComponents;

        $response->answered_at = now();
    }

    /**
     * Obtém a preventiva relacionada à unidade do ciclo.
     */
    private function getPreventive(
        PreventiveCycleUnit $cycleUnit
    ): ?Preventive {
        $cycleUnit->loadMissing(
            'cycle.preventive'
        );

        return $cycleUnit->cycle?->preventive;
    }

    /**
     * Coloca a preventiva em execução quando
     * a primeira resposta é registrada.
     */
    private function startPreventiveIfNecessary(
        Preventive $preventive
    ): void {
        if (
            $preventive->status !==
            StatusPreventiveEnum::NEW
        ) {
            return;
        }

        $preventive->start_at = now();

        $preventive->status =
            StatusPreventiveEnum::IN_PROGRESS;

        $preventive->save();
    }

    /**
     * Verifica se todas as atividades do ciclo
     * já possuem uma resposta.
     */
    private function finalizeIfCompleted(
        Preventive $preventive
    ): void {
        $cycle = $this->getCurrentCycle(
            $preventive
        );

        if (! $cycle) {
            return;
        }

        $this->loadCycleExecutionRelations(
            $cycle
        );

        if ($this->hasPendingActivities($cycle)) {
            return;
        }

        $this->finalizePreventiveService
            ->execute($preventive);
    }

    /**
     * Localiza o ciclo atual da preventiva.
     */
    private function getCurrentCycle(
        Preventive $preventive
    ) {
        return $preventive->cycles()
            ->where(
                'sequence',
                $preventive->current_cycle
            )
            ->first();
    }

    /**
     * Carrega as relações necessárias para verificar
     * as atividades respondidas.
     */
    private function loadCycleExecutionRelations(
        $cycle
    ): void {
        $cycle->load([
            'units.activities',
            'units.activityResponses',
        ]);
    }

    /**
     * Verifica se existe alguma atividade ainda
     * sem resposta no ciclo.
     */
    private function hasPendingActivities(
        $cycle
    ): bool {
        foreach ($cycle->units as $unit) {
            if ($this->unitHasPendingActivities($unit)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Verifica se uma unidade possui alguma atividade
     * sem resposta.
     */
    private function unitHasPendingActivities(
        PreventiveCycleUnit $unit
    ): bool {
        $answeredActivityIds =
            $unit->activityResponses
                ->pluck('snapshot_rule_activity_id')
                ->unique();

        return $unit->activities->contains(
            fn (
                PreventiveCycleUnitActivity $activity
            ): bool =>
                ! $answeredActivityIds->contains(
                    $activity->snapshot_rule_activity_id
                )
        );
    }
}

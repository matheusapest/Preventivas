<?php

declare(strict_types=1);

namespace App\Services\Preventive;

use App\Models\Preventive\Preventive;
use App\Models\Preventive\PreventiveActivityResponse;
use App\Models\Preventive\PreventiveCycle;
use App\Models\Preventive\PreventiveCycleUnit;
use App\Models\Preventive\PreventiveSnapshotRuleActivity;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ExecutePreventiveActivityService
{
    /**
     * Registra a execução de uma atividade dentro de uma unidade
     * de um ciclo da preventiva.
     */
    public function execute(
        Preventive $preventive,
        PreventiveCycleUnit $cycleUnit,
        PreventiveSnapshotRuleActivity $snapshotActivity,
        array $data
    ): PreventiveActivityResponse {
        return DB::transaction(function () use (
            $preventive,
            $cycleUnit,
            $snapshotActivity,
            $data
        ) {
            $cycle = $this->validateExecutionContext(
                $preventive,
                $cycleUnit,
                $snapshotActivity
            );

            $this->validateResponse(
                $snapshotActivity,
                $data
            );

            $existingResponse = PreventiveActivityResponse::query()
                ->where('preventive_cycle_unit_id', $cycleUnit->id)
                ->where(
                    'snapshot_rule_activity_id',
                    $snapshotActivity->id
                )
                ->first();

            if ($existingResponse) {
                throw ValidationException::withMessages([
                    'activity' =>
                        'Esta atividade já foi respondida para esta unidade neste ciclo.',
                ]);
            }

            $response = PreventiveActivityResponse::create([
                'preventive_cycle_unit_id' =>
                    $cycleUnit->id,

                'snapshot_rule_activity_id' =>
                    $snapshotActivity->id,

                'result' =>
                    $data['result'],

                'final_status' =>
                    $data['final_status'] ?? null,

                'observation' =>
                    $data['observation'] ?? null,

                'response_data' =>
                    $data['response_data'] ?? null,

                'started_at' =>
                    $data['started_at'] ?? now(),

                'answered_at' =>
                    now(),
            ]);

            $this->completeCycleIfNecessary($cycle);

            return $response->fresh();
        });
    }

    /**
     * Valida se a unidade e atividade pertencem
     * ao contexto da preventiva que está sendo executada.
     */
    private function validateExecutionContext(
        Preventive $preventive,
        PreventiveCycleUnit $cycleUnit,
        PreventiveSnapshotRuleActivity $snapshotActivity
    ): PreventiveCycle {
        $cycle = $cycleUnit->cycle;

        if (!$cycle) {
            throw ValidationException::withMessages([
                'cycle' => 'O ciclo da preventiva não foi encontrado.',
            ]);
        }

        if ($cycle->preventive_id !== $preventive->id) {
            throw ValidationException::withMessages([
                'cycle' =>
                    'O ciclo informado não pertence à preventiva.',
            ]);
        }

        if ($cycle->status !== 'pending') {
            throw ValidationException::withMessages([
                'cycle' =>
                    'Este ciclo não está disponível para execução.',
            ]);
        }

        $cycleActivityExists = $cycleUnit->activities()
            ->where(
                'snapshot_rule_activity_id',
                $snapshotActivity->id
            )
            ->exists();

        if (!$cycleActivityExists) {
            throw ValidationException::withMessages([
                'activity' =>
                    'A atividade informada não pertence à unidade deste ciclo.',
            ]);
        }

        if ($preventive->status === 'approved') {
            throw ValidationException::withMessages([
                'preventive' =>
                    'Esta preventiva já foi aprovada e não pode mais receber respostas.',
            ]);
        }

        return $cycle;
    }

    /**
     * Valida os dados da resposta conforme a atividade.
     */
    private function validateResponse(
        PreventiveSnapshotRuleActivity $snapshotActivity,
        array $data
    ): void {
        $result = $data['result'] ?? null;

        if (!$result) {
            throw ValidationException::withMessages([
                'result' =>
                    'O resultado da atividade é obrigatório.',
            ]);
        }

        $finalStatus = $data['final_status'] ?? null;

        if (!$finalStatus) {
            throw ValidationException::withMessages([
                'final_status' =>
                    'A situação final da atividade é obrigatória.',
            ]);
        }

        if (
            $result === 'non_conform' &&
            empty(trim((string) ($data['observation'] ?? '')))
        ) {
            throw ValidationException::withMessages([
                'observation' =>
                    'Informe a observação para uma atividade não conforme.',
            ]);
        }

        if (
            $snapshotActivity->activity_type === 'operational_composition' &&
            !array_key_exists('response_data', $data)
        ) {
            throw ValidationException::withMessages([
                'response_data' =>
                    'Os dados da composição operacional são obrigatórios.',
            ]);
        }
    }

    /**
     * Verifica se todas as atividades de todas as unidades
     * do ciclo foram respondidas.
     */
    private function completeCycleIfNecessary(
        PreventiveCycle $cycle
    ): void {
        $pendingActivities = $cycle->units()
            ->whereHas('activities', function ($query) {
                $query->whereDoesntHave('response');
            })
            ->exists();

        if ($pendingActivities) {
            return;
        }

        $cycle->update([
            'status' => 'completed',
        ]);

        $cycle->preventive()->update([
            'status' => 'pending_approval',
            'completed_at' => now(),
        ]);
    }
}

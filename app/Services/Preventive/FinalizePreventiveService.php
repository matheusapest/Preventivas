<?php

declare(strict_types=1);

namespace App\Services\Preventive;

use App\Enums\StatusCycleEnum;
use App\Enums\StatusPreventiveEnum;
use App\Models\Preventive;
use App\Models\PreventiveCycle;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class FinalizePreventiveService
{
    /**
     * Finaliza automaticamente a preventiva.
     *
     * Esse método somente deve ser utilizado quando
     * não existem mais atividades pendentes.
     */
    public function execute(
        Preventive $preventive
    ): Preventive {
        $this->validatePreventiveStatus($preventive);

        $cycle = $this->getCurrentCycle($preventive);

        $this->validateCycle($cycle);

        $this->validateNoPendingActivities($cycle);

        return DB::transaction(function () use (
            $preventive,
            $cycle
        ): Preventive {

            $this->finishCycle($cycle);

            $this->sendPreventiveToApproval($preventive);

            return $preventive;
        });
    }

    /**
     * Finaliza manualmente a preventiva mesmo existindo
     * unidades ou atividades pendentes.
     *
     * A justificativa do técnico é obrigatória.
     */
    public function executeWithPending(
        Preventive $preventive,
        string $observation
    ): Preventive {
        $this->validatePreventiveStatus($preventive);

        $cycle = $this->getCurrentCycle($preventive);

        $this->validateCycle($cycle);

        $observation = trim($observation);

        if ($observation === '') {
            throw ValidationException::withMessages([
                'observation' =>
                    'Informe o motivo para finalizar a preventiva com pendências.',
            ]);
        }

        return DB::transaction(function () use (
            $preventive,
            $cycle,
            $observation
        ): Preventive {

            /*
             * O Cycle continua contendo exatamente as unidades
             * e atividades que foram executadas.
             *
             * As pendências NÃO são marcadas como concluídas.
             */
            $this->finishCycle($cycle);

            /*
             * A justificativa da finalização manual será
             * registrada na revisão do Cycle.
             *
             * Neste momento ainda não houve revisão do gestor,
             * portanto reviewed_by/reviewed_at permanecem NULL.
             */
            $cycle->review_observation = $observation;

            $cycle->save();

            $this->sendPreventiveToApproval($preventive);

            return $preventive;
        });
    }

    /**
     * Garante que a preventiva está em execução.
     */
    private function validatePreventiveStatus(
        Preventive $preventive
    ): void {
        if (
            $preventive->status !==
            StatusPreventiveEnum::IN_PROGRESS
        ) {
            throw ValidationException::withMessages([
                'preventive' =>
                    'A preventiva não está em execução.',
            ]);
        }
    }

    /**
     * Localiza o ciclo atual da preventiva.
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
     * Garante que o ciclo ainda pode ser finalizado.
     */
    private function validateCycle(
        PreventiveCycle $cycle
    ): void {
        if (
            $cycle->status ===
            StatusCycleEnum::FINISHED
        ) {
            throw ValidationException::withMessages([
                'cycle' =>
                    'O ciclo atual já foi finalizado.',
            ]);
        }
    }

    /**
     * Impede que a finalização automática ocorra
     * enquanto existirem atividades pendentes.
     */
    private function validateNoPendingActivities(
        PreventiveCycle $cycle
    ): void {
        $cycle->loadMissing([
            'units.activities',
            'units.activityResponses',
        ]);

        foreach ($cycle->units as $unit) {

            $totalActivities =
                $unit->activities->count();

            $answeredActivities =
                $unit->activities
                    ->filter(function ($activity) use ($unit) {
                        return $unit->activityResponses
                            ->contains(
                                'snapshot_rule_activity_id',
                                $activity->snapshot_rule_activity_id
                            );
                    })
                    ->count();

            if (
                $answeredActivities <
                $totalActivities
            ) {
                throw ValidationException::withMessages([
                    'cycle' =>
                        'A preventiva ainda possui atividades pendentes.',
                ]);
            }
        }
    }

    /**
     * Finaliza o Cycle.
     */
    private function finishCycle(
        PreventiveCycle $cycle
    ): void {
        $cycle->status =
            StatusCycleEnum::FINISHED;

        $cycle->save();
    }

    /**
     * Encaminha a preventiva para aprovação do gestor.
     */
    private function sendPreventiveToApproval(
        Preventive $preventive
    ): void {
        $preventive->status =
            StatusPreventiveEnum::PENDING_APPROVAL;

        $preventive->completed_at =
            now();

        $preventive->save();
    }
}

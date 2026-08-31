<?php

declare(strict_types=1);

namespace App\Services\Preventive\Validation;

use App\Enums\CycleReviewStatusEnum;
use App\Enums\StatusCycleEnum;
use App\Enums\StatusPreventiveEnum;
use App\Models\Preventive\Preventive;
use App\Models\Preventive\PreventiveCycle;
use App\Models\Access\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RejectPreventiveService
{
    /**
     * Reprova o ciclo atual da preventiva.
     *
     * O ciclo permanece FINALIZADO, pois sua execução já terminou.
     *
     * A reprovação é registrada através de:
     *
     * - review_status
     * - reviewed_by
     * - reviewed_at
     * - review_observation
     *
     * A preventiva retorna para IN_PROGRESS para que uma
     * continuação possa ser criada posteriormente.
     */
    public function execute(
        Preventive $preventive,
        User $user,
        string $observation
    ): Preventive {
        $this->validatePreventive($preventive);

        $observation = trim($observation);

        if ($observation === '') {
            throw ValidationException::withMessages([
                'review_observation' =>
                    'O motivo da reprovação é obrigatório.',
            ]);
        }

        $cycle = $this->getCurrentCycle($preventive);

        $this->validateCycle($cycle);

        return DB::transaction(function () use (
            $preventive,
            $cycle,
            $user,
            $observation
        ): Preventive {

            /*
             * ---------------------------------------------------------
             * REGISTRA A REVISÃO DO CICLO
             * ---------------------------------------------------------
             */

            $cycle->review_status =
                CycleReviewStatusEnum::REJECTED;

            $cycle->reviewed_by =
                $user->id;

            $cycle->reviewed_at =
                now();

            $cycle->review_observation =
                $observation;

            $cycle->save();

            /*
             * ---------------------------------------------------------
             * DEVOLVE A PREVENTIVA PARA EXECUÇÃO
             * ---------------------------------------------------------
             *
             * O Cycle anterior permanece FINISHED.
             *
             * Um novo Cycle será criado posteriormente pelo
             * serviço responsável pela continuidade.
             */

            $preventive->status =
                StatusPreventiveEnum::IN_PROGRESS;

            $preventive->save();

            return $preventive;
        });
    }

    /**
     * Garante que a preventiva está aguardando aprovação.
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
                    'A preventiva não está aguardando aprovação.',
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
     * Garante que o ciclo já foi finalizado pelo técnico.
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
    }
}

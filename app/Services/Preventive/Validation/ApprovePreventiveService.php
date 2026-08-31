<?php

declare(strict_types=1);

namespace App\Services\Preventive\Validation;

use App\Enums\CycleReviewStatusEnum;
use App\Enums\StatusPreventiveEnum;
use App\Enums\StatusCycleEnum;
use App\Models\Preventive\Preventive;
use App\Models\Preventive\PreventiveCycle;
use App\Models\Access\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ApprovePreventiveService
{
    /**
     * Aprova uma preventiva que está aguardando validação.
     *
     * A aprovação também registra a revisão no ciclo atual.
     */
    public function execute(
        Preventive $preventive,
        User $user,
        ?string $observation = null
    ): Preventive {
        $this->validatePreventive($preventive);

        return DB::transaction(function () use (
            $preventive,
            $user,
            $observation
        ): Preventive {

            /*
             * ---------------------------------------------------------
             * LOCALIZA O CYCLE ATUAL
             * ---------------------------------------------------------
             */

            $cycle = $this->getCurrentCycle($preventive);

            $this->validateCycle($cycle);

            /*
             * ---------------------------------------------------------
             * REGISTRA A REVISÃO DO CYCLE
             * ---------------------------------------------------------
             */

            $cycle->review_status =
                CycleReviewStatusEnum::APPROVED;

            $cycle->reviewed_by =
                $user->id;

            $cycle->reviewed_at =
                now();

            $cycle->review_observation =
                $observation !== null
                    ? trim($observation)
                    : null;

            $cycle->save();

            /*
             * ---------------------------------------------------------
             * ATUALIZA A PREVENTIVA
             * ---------------------------------------------------------
             */

            $preventive->status =
                StatusPreventiveEnum::APPROVED;

            $preventive->approved_at =
                now();

            $preventive->approved_by =
                $user->id;

            $preventive->save();

            return $preventive;
        });
    }

    /**
     * Garante que a preventiva está aguardando validação.
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
     * Localiza o ciclo que está sendo validado.
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
                'cycle' =>
                    'O ciclo atual da preventiva não foi encontrado.',
            ]);
        }

        return $cycle;
    }

    /**
     * Garante que o ciclo esteja finalizado antes da aprovação.
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

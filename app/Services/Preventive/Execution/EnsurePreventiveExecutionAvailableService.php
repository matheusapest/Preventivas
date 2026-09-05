<?php

declare(strict_types=1);

namespace App\Services\Preventive\Execution;

use App\Enums\StatusCycleEnum;
use App\Enums\StatusPreventiveEnum;
use App\Models\Preventive\Preventive;
use App\Models\Preventive\PreventiveCycle;
use Illuminate\Validation\ValidationException;

class EnsurePreventiveExecutionAvailableService
{
    /**
     * Garante que a preventiva possui um ciclo disponível
     * para execução pelo técnico.
     *
     * Retorna o ciclo atual quando a execução é permitida.
     */
    public function execute(
        Preventive $preventive
    ): PreventiveCycle {
        /*
         * ---------------------------------------------------------
         * 1. STATUS DA PREVENTIVA
         * ---------------------------------------------------------
         *
         * A Preventiva precisa estar em execução.
         */
        if (
            $preventive->status !==
            StatusPreventiveEnum::IN_PROGRESS
        ) {
            throw ValidationException::withMessages([
                'preventive' =>
                    'A preventiva não está disponível para execução.',
            ]);
        }

        /*
         * ---------------------------------------------------------
         * 2. CICLO ATUAL
         * ---------------------------------------------------------
         */

        $cycle = $preventive->cycles()
            ->where(
                'sequence',
                $preventive->current_cycle
            )
            ->first();

        if (! $cycle) {
            throw ValidationException::withMessages([
                'cycle' =>
                    'A preventiva não possui um ciclo disponível para execução.',
            ]);
        }

        /*
         * ---------------------------------------------------------
         * 3. STATUS DO CICLO
         * ---------------------------------------------------------
         *
         * Somente ciclos novos ou em execução podem receber
         * ações do técnico.
         *
         * Um ciclo FINISHED nunca pode ser alterado novamente.
         */
        if (
            ! in_array(
                $cycle->status,
                [
                    StatusCycleEnum::NEW,
                    StatusCycleEnum::IN_PROGRESS,
                ],
                true
            )
        ) {
            throw ValidationException::withMessages([
                'cycle' =>
                    'O ciclo atual já foi finalizado e aguarda a criação de um novo ciclo.',
            ]);
        }

        return $cycle;
    }
}

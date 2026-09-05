<?php

declare(strict_types=1);

namespace App\Services\Preventive\Execution;

use App\Enums\StatusCycleEnum;
use App\Enums\StatusPreventiveEnum;
use App\Models\Preventive\Preventive;
use Illuminate\Database\Eloquent\Builder;

class GetPreventiveExecutionService
{
    /**
     * Retorna as preventivas do técnico organizadas
     * por situação dentro do fluxo de execução.
     */
    public function execute(
        int $userId,
        array $filters = []
    ): array {
        $baseQuery = Preventive::query()
            ->with([
                'branch',
                'preventiveType',
                'preventiveProfile',
                'cycles',
            ])
            ->where('assigned_user_id', $userId);

        /*
         * ============================================================
         * CONTADORES
         * ============================================================
         */

        $newCount = (clone $baseQuery)
            ->where(
                'status',
                StatusPreventiveEnum::NEW
            )
            ->count();

        /*
         * Uma Preventiva em andamento somente é considerada
         * disponível para execução quando possui um ciclo atual
         * em estado executável.
         */
        $inProgressCount = (clone $baseQuery)
            ->where(
                'status',
                StatusPreventiveEnum::IN_PROGRESS
            )
            ->whereHas('cycles', function (Builder $query) {
                $query
                    ->whereColumn(
                        'preventive_cycles.sequence',
                        'preventives.current_cycle'
                    )
                    ->whereIn('status', [
                        StatusCycleEnum::NEW,
                        StatusCycleEnum::IN_PROGRESS,
                    ]);
            })
            ->count();

        $pendingApprovalCount = (clone $baseQuery)
            ->where(
                'status',
                StatusPreventiveEnum::PENDING_APPROVAL
            )
            ->count();

        $approvedCount = (clone $baseQuery)
            ->where(
                'status',
                StatusPreventiveEnum::APPROVED
            )
            ->count();

        $totalCount = (clone $baseQuery)->count();

        /*
         * ============================================================
         * FILTROS
         * ============================================================
         */

        $query = clone $baseQuery;

        $this->applyFilters(
            $query,
            $filters
        );

        /*
         * ============================================================
         * PREVENTIVAS
         * ============================================================
         */

        $preventives = $query
            ->latest()
            ->get();

        /*
         * ============================================================
         * DISPONIBILIDADE PARA EXECUÇÃO
         * ============================================================
         */

        $preventives->each(
            function (Preventive $preventive): void {
                $preventive->setAttribute(
                    'can_execute',
                    $this->canExecute($preventive)
                );
            }
        );

        return [
            'preventives' => $preventives,
            'totalCount' => $totalCount,
            'newCount' => $newCount,
            'inProgressCount' => $inProgressCount,
            'pendingApprovalCount' => $pendingApprovalCount,
            'approvedCount' => $approvedCount,
            'currentStatus' => $filters['status'] ?? null,
        ];
    }

    /**
     * Determina se a Preventiva possui um ciclo disponível
     * para execução pelo técnico.
     */
    private function canExecute(
        Preventive $preventive
    ): bool {
        /*
         * Somente Preventivas novas ou em execução podem
         * entrar no fluxo de execução.
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
         * Localiza o ciclo correspondente ao current_cycle
         * registrado na Preventiva.
         */
        $currentCycle = $preventive->cycles
            ->firstWhere(
                'sequence',
                $preventive->current_cycle
            );

        if (! $currentCycle) {
            return false;
        }

        /*
         * Somente ciclos novos ou em execução podem receber
         * execução do técnico.
         */
        return in_array(
            $currentCycle->status,
            [
                StatusCycleEnum::NEW,
                StatusCycleEnum::IN_PROGRESS,
            ],
            true
        );
    }

    /**
     * Aplica os filtros utilizados pelo painel.
     */
    private function applyFilters(
        Builder $query,
        array $filters
    ): void {
        /*
         * ------------------------------------------------------------
         * Busca
         * ------------------------------------------------------------
         */

        if (! empty($filters['search'])) {
            $search = trim($filters['search']);

            $query->where(function (Builder $query) use ($search) {
                $query
                    ->where('id', $search)
                    ->orWhereHas(
                        'branch',
                        function (Builder $query) use ($search) {
                            $query->where(
                                'name',
                                'like',
                                "%{$search}%"
                            );
                        }
                    )
                    ->orWhereHas(
                        'preventiveType',
                        function (Builder $query) use ($search) {
                            $query->where(
                                'name',
                                'like',
                                "%{$search}%"
                            );
                        }
                    )
                    ->orWhereHas(
                        'preventiveProfile',
                        function (Builder $query) use ($search) {
                            $query->where(
                                'name',
                                'like',
                                "%{$search}%"
                            );
                        }
                    );
            });
        }

        /*
         * ------------------------------------------------------------
         * Status
         * ------------------------------------------------------------
         */

        if (! empty($filters['status'])) {
            $status = StatusPreventiveEnum::tryFrom(
                $filters['status']
            );

            if ($status) {
                $query->where(
                    'status',
                    $status
                );
            }
        }
    }
}

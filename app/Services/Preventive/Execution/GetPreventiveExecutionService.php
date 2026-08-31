<?php

declare(strict_types=1);

namespace App\Services\Preventive\Execution;

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
            ])
            ->where('assigned_user_id', $userId);

        /*
         * ============================================================
         * CONTADORES
         * ============================================================
         */

        $newCount = (clone $baseQuery)
            ->where('status', StatusPreventiveEnum::NEW)
            ->count();

        $inProgressCount = (clone $baseQuery)
            ->where('status', StatusPreventiveEnum::IN_PROGRESS)
            ->count();

        $pendingApprovalCount = (clone $baseQuery)
            ->where('status', StatusPreventiveEnum::PENDING_APPROVAL)
            ->count();

        $approvedCount = (clone $baseQuery)
            ->where('status', StatusPreventiveEnum::APPROVED)
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
                $query->where('status', $status);
            }
        }
    }
}

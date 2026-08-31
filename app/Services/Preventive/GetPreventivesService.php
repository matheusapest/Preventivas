<?php

declare(strict_types=1);

namespace App\Services\Preventive;

use App\Enums\CycleReviewStatusEnum;
use App\Enums\StatusCycleEnum;
use App\Enums\StatusPreventiveEnum;
use App\Models\Branch;
use App\Models\Preventive;
use App\Models\PreventiveType;
use Illuminate\Database\Eloquent\Builder;

class GetPreventivesService
{
    /**
     * Retorna os dados necessários para o painel
     * de gestão das preventivas.
     */
    public function execute(
        array $filters = []
    ): array {
        /*
         * ============================================================
         * CONTADORES
         * ============================================================
         */

        $baseQuery = Preventive::query();

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

        /*
         * ============================================================
         * LISTAGEM
         * ============================================================
         */

        $query = Preventive::query()
            ->select([
                'id',
                'branch_id',
                'preventive_type_id',
                'preventive_profile_id',
                'assigned_user_id',
                'created_by',
                'start_date',
                'start_at',
                'due_date',
                'status',
                'current_cycle',
                'completed_at',
                'approved_at',
                'approved_by',
                'created_at',
                'updated_at',
            ])
            ->with([
                'branch',
                'preventiveType',
                'preventiveProfile',
                'assignedUser',
                'createdBy',

                /*
                 * Carrega os Cycles para que a tabela possa
                 * identificar o Cycle atual e sua revisão.
                 */
                'cycles',
            ]);

        $this->applyFilters(
            $query,
            $filters
        );

        $preventives = $query
            ->latest()
            ->paginate()
            ->withQueryString();

        /*
         * ============================================================
         * DADOS DOS FILTROS
         * ============================================================
         */

        $branches = Branch::query()
            ->orderBy('name')
            ->get([
                'id',
                'name',
            ]);

        $preventiveTypes = PreventiveType::query()
            ->orderBy('name')
            ->get([
                'id',
                'name',
            ]);

        $statuses = StatusPreventiveEnum::cases();

        /*
         * ============================================================
         * PREPARAÇÃO DOS DADOS DE CICLO
         * ============================================================
         */

        $preventives->getCollection()->transform(
            function (Preventive $preventive) {

                $currentCycle = $preventive->cycles
                    ->first(
                        fn ($cycle) =>
                            $cycle->sequence ===
                            $preventive->current_cycle
                    );

                /*
                 * A continuidade somente fica disponível quando
                 * o Cycle atual estiver finalizado e rejeitado.
                 */
                $canContinue =
                    $currentCycle !== null
                    && $currentCycle->status ===
                        StatusCycleEnum::FINISHED
                    && $currentCycle->review_status ===
                        CycleReviewStatusEnum::REJECTED;

                /*
                 * Dados auxiliares para a Blade.
                 */
                $preventive->setAttribute(
                    'current_cycle_model',
                    $currentCycle
                );

                $preventive->setAttribute(
                    'can_continue',
                    $canContinue
                );

                return $preventive;
            }
        );

        /*
         * ============================================================
         * RETORNO
         * ============================================================
         */

        return [
            'preventives' => $preventives,

            /*
             * Contadores
             */
            'newCount' => $newCount,
            'inProgressCount' => $inProgressCount,
            'pendingApprovalCount' => $pendingApprovalCount,
            'approvedCount' => $approvedCount,

            /*
             * Cards de status
             */
            'statusFilters' => $this->buildStatusFilters(
                newCount: $newCount,
                inProgressCount: $inProgressCount,
                pendingApprovalCount: $pendingApprovalCount,
                approvedCount: $approvedCount,
            ),

            /*
             * Dados dos filtros
             */
            'branches' => $branches,
            'preventiveTypes' => $preventiveTypes,
            'statuses' => $statuses,
        ];
    }




    /**
     * Aplica os filtros da listagem.
     */
    private function applyFilters(
        Builder $query,
        array $filters
    ): void {
        /*
         * ============================================================
         * BUSCA
         * ============================================================
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
                    )

                    ->orWhereHas(
                        'assignedUser',
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
         * ============================================================
         * STATUS
         * ============================================================
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


        /*
         * ============================================================
         * FILIAL
         * ============================================================
         */

        if (! empty($filters['branch_id'])) {

            $query->where(
                'branch_id',
                $filters['branch_id']
            );
        }


        /*
         * ============================================================
         * TIPO DE PREVENTIVA
         * ============================================================
         */

        if (! empty($filters['preventive_type_id'])) {

            $query->where(
                'preventive_type_id',
                $filters['preventive_type_id']
            );
        }
    }


    /**
     * Monta os dados dos cards de status.
     *
     * Os contadores já foram calculados pelo execute(),
     * portanto este método não realiza consultas ao banco.
     */
    private function buildStatusFilters(
        int $newCount,
        int $inProgressCount,
        int $pendingApprovalCount,
        int $approvedCount
    ): array {
        return [
            [
                'status' => StatusPreventiveEnum::NEW,
                'label' => 'Novas',
                'count' => $newCount,
                'description' => 'Aguardando início',
                'color' => 'blue',
            ],

            [
                'status' => StatusPreventiveEnum::IN_PROGRESS,
                'label' => 'Em andamento',
                'count' => $inProgressCount,
                'description' => 'Execuções em andamento',
                'color' => 'amber',
            ],

            [
                'status' => StatusPreventiveEnum::PENDING_APPROVAL,
                'label' => 'Aguardando aprovação',
                'count' => $pendingApprovalCount,
                'description' => 'Aguardando o gestor',
                'color' => 'indigo',
            ],

            [
                'status' => StatusPreventiveEnum::APPROVED,
                'label' => 'Aprovadas',
                'count' => $approvedCount,
                'description' => 'Validadas pelo gestor',
                'color' => 'emerald',
            ],
        ];
    }
}

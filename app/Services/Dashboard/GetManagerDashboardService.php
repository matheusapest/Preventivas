<?php

declare(strict_types=1);

namespace App\Services\Dashboard;

use App\Enums\CycleReviewStatusEnum;
use App\Enums\MaintenanceShipmentStatus;
use App\Enums\StatusPreventiveEnum;
use App\Models\Maintenance\MaintenanceShipment;
use App\Models\Preventive\Preventive;
use Carbon\Carbon;

class GetManagerDashboardService
{
    /**
     * Retorna os indicadores e alertas do Dashboard do gestor.
     */
    public function execute(): array
    {
        return [
            'maintenance' => $this->maintenanceIndicators(),
            'preventives' => $this->preventiveIndicators(),
            'alerts' => $this->alerts(),
        ];
    }

    /**
     * Indicadores relacionados ao reparo externo.
     */
    private function maintenanceIndicators(): array
    {
        $pendingReceiptQuery = MaintenanceShipment::query()
            ->where(
                'status',
                MaintenanceShipmentStatus::SENT
            )
            ->whereDoesntHave('receipt');

        $overdueReceiptQuery = (clone $pendingReceiptQuery)
            ->where(
                'sent_at',
                '<=',
                now()->subDays(7)
            );

        return [
            'pending_receipt' => $pendingReceiptQuery->count(),

            'overdue_receipt' => $overdueReceiptQuery->count(),
        ];
    }

    /**
     * Indicadores relacionados às preventivas.
     */
    private function preventiveIndicators(): array
    {
        $executedStart = Carbon::now()->startOfMonth();
        $executedEnd = Carbon::now()->endOfMonth();

        return [
            /*
             * Preventivas programadas para uma data futura.
             */
            'programmed' => Preventive::query()
                ->where(
                    'status',
                    StatusPreventiveEnum::NEW
                )
                ->whereDate(
                    'start_date',
                    '>',
                    today()
                )
                ->count(),

            /*
             * Preventivas que já chegaram à data de início,
             * mas ainda não foram iniciadas.
             */
            'pending_execution' => Preventive::query()
                ->where(
                    'status',
                    StatusPreventiveEnum::NEW
                )
                ->whereDate(
                    'start_date',
                    '<=',
                    today()
                )
                ->count(),

            /*
             * Preventivas atualmente em execução.
             */
            'in_execution' => Preventive::query()
                ->where(
                    'status',
                    StatusPreventiveEnum::IN_PROGRESS
                )
                ->count(),

            /*
             * Preventivas finalizadas pelo técnico e
             * aguardando aprovação do gestor.
             */
            'pending_approval' => Preventive::query()
                ->where(
                    'status',
                    StatusPreventiveEnum::PENDING_APPROVAL
                )
                ->count(),

            /*
             * Total de preventivas atualmente aprovadas.
             *
             * Representa o estado atual, não produtividade
             * do período.
             */
            'approved' => Preventive::query()
                ->where(
                    'status',
                    StatusPreventiveEnum::APPROVED
                )
                ->count(),

            /*
             * Preventivas efetivamente finalizadas no mês.
             *
             * Uma preventiva só entra neste indicador depois
             * da aprovação do gestor.
             */
            'executed' => Preventive::query()
                ->where(
                    'status',
                    StatusPreventiveEnum::APPROVED
                )
                ->whereBetween(
                    'approved_at',
                    [
                        $executedStart,
                        $executedEnd,
                    ]
                )
                ->count(),

            /*
             * Preventivas cujo ciclo atual foi reprovado
             * e que aguardam a criação de um novo ciclo.
             */
            'rejected_awaiting_cycle' => Preventive::query()
                ->where(
                    'status',
                    StatusPreventiveEnum::IN_PROGRESS
                )
                ->whereHas(
                    'cycles',
                    function ($query) {
                        $query
                            ->whereColumn(
                                'preventive_cycles.sequence',
                                'preventives.current_cycle'
                            )
                            ->where(
                                'review_status',
                                CycleReviewStatusEnum::REJECTED
                            );
                    }
                )
                ->count(),
        ];
    }

    /**
     * Retorna os itens que exigem atenção do gestor.
     */
    private function alerts(): array
    {
        return [
            'maintenance_overdue' =>
                $this->maintenanceOverdueAlerts(),

            'preventives_pending_approval' =>
                $this->preventivesPendingApprovalAlerts(),

            'preventives_rejected' =>
                $this->preventivesRejectedAlerts(),
        ];
    }

    /**
     * Equipamentos enviados para reparo há mais de 7 dias
     * e que ainda não possuem recebimento.
     */
    private function maintenanceOverdueAlerts()
    {
        return MaintenanceShipment::query()
            ->where(
                'status',
                MaintenanceShipmentStatus::SENT
            )
            ->whereDoesntHave('receipt')
            ->where(
                'sent_at',
                '<=',
                now()->subDays(7)
            )
            ->with([
                'maintenanceOrder.equipment',
                'originBranch',
                'company',
            ])
            ->orderBy('sent_at')
            ->get()
            ->map(function (MaintenanceShipment $shipment) {
                return [
                    'id' => $shipment->id,

                    'equipment' =>
                        $shipment
                            ->maintenanceOrder
                            ?->equipment
                            ?->name,

                    'asset_number' =>
                        $shipment
                            ->maintenanceOrder
                            ?->equipment
                            ?->asset_number,

                    'branch' =>
                        $shipment
                            ->originBranch
                            ?->name,

                    'company' =>
                        $shipment
                            ->company
                            ?->name,

                    'sent_at' =>
                        $shipment->sent_at,

                    'days_away' =>
                        $shipment->sent_at
                            ?->diffInDays(now()),
                ];
            });
    }

    /**
     * Preventivas finalizadas pelo técnico e
     * aguardando aprovação do gestor.
     */
    private function preventivesPendingApprovalAlerts()
    {
        return Preventive::query()
            ->where(
                'status',
                StatusPreventiveEnum::PENDING_APPROVAL
            )
            ->with([
                'branch',
                'preventiveType',
                'assignedUser',
            ])
            ->orderBy('due_date')
            ->get();
    }

    /**
     * Preventivas reprovadas no ciclo atual e que
     * aguardam a criação de um novo ciclo.
     */
    private function preventivesRejectedAlerts()
    {
        return Preventive::query()
            ->where(
                'status',
                StatusPreventiveEnum::IN_PROGRESS
            )
            ->whereHas(
                'cycles',
                function ($query) {
                    $query
                        ->whereColumn(
                            'preventive_cycles.sequence',
                            'preventives.current_cycle'
                        )
                        ->where(
                            'review_status',
                            CycleReviewStatusEnum::REJECTED
                        );
                }
            )
            ->with([
                'branch',
                'preventiveType',
                'assignedUser',
            ])
            ->orderBy('due_date')
            ->get();
    }
}

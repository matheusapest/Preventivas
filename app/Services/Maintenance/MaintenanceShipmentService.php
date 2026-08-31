<?php

declare(strict_types=1);

namespace App\Services\Maintenance;

use App\Enums\MaintenanceOrderStatus;
use App\Enums\MaintenanceShipmentStatus;
use App\Enums\OperationalStatus;
use App\Models\Equipment\Equipment;
use App\Models\Maintenance\MaintenanceOrder;
use App\Models\Maintenance\MaintenanceShipment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class MaintenanceShipmentService
{
    /**
     * Envia um equipamento para reparo externo.
     *
     * Este método representa exclusivamente o primeiro envio
     * de um equipamento e a criação de uma nova OS.
     */
    public function create(
        int $equipmentId,
        int $companyId,
        int $originBranchId,
        string $sentAt,
        ?string $invoiceNumber,
        string $defectDescription,
        ?string $observation,
        int $userId
    ): MaintenanceShipment {
        return DB::transaction(function () use (
            $equipmentId,
            $companyId,
            $originBranchId,
            $invoiceNumber,
            $defectDescription,
            $observation,
            $userId,
            $sentAt
        ) {

            /*
             * Bloqueia o equipamento durante a operação.
             */
            $equipment = Equipment::query()
                ->lockForUpdate()
                ->findOrFail($equipmentId);

            /*
             * Equipamentos inativos não podem iniciar um reparo.
             */
            if (! $equipment->active) {
                throw new RuntimeException(
                    'O equipamento está inativo e não pode ser enviado para reparo.'
                );
            }

            /*
             * Equipamentos descartados não podem voltar ao processo.
             */
            if (
                $equipment->operational_status === OperationalStatus::DISCARDED
            ) {
                throw new RuntimeException(
                    'O equipamento está descartado e não pode ser enviado para reparo.'
                );
            }

            /*
             * Procura a OS mais recente do equipamento.
             */
            $maintenanceOrder = MaintenanceOrder::query()
                ->where('equipment_id', $equipment->id)
                ->latest('id')
                ->first();

            /*
             * Verifica se o equipamento possui uma OS aberta.
             */
            if (
                $maintenanceOrder &&
                in_array(
                    $maintenanceOrder->status,
                    [
                        MaintenanceOrderStatus::IN_REPAIR,
                        MaintenanceOrderStatus::IN_VALIDATION,
                    ],
                    true
                )
            ) {
                if (
                    $maintenanceOrder->status ===
                    MaintenanceOrderStatus::IN_REPAIR
                ) {
                    throw new RuntimeException(
                        'O equipamento já possui uma ordem de serviço em andamento.'
                    );
                }

                throw new RuntimeException(
                    'O equipamento possui uma ordem de serviço aguardando validação.'
                );
            }

            /*
             * Neste ponto podemos iniciar uma nova OS.
             */
            $maintenanceOrder = MaintenanceOrder::create([
                'equipment_id' => $equipment->id,
                'status' => MaintenanceOrderStatus::IN_REPAIR,
            ]);

            /*
             * O primeiro envio sempre começa com sequence 1.
             */
            $shipment = MaintenanceShipment::create([
                'maintenance_order_id' => $maintenanceOrder->id,
                'sequence' => 1,
                'company_id' => $companyId,
                'origin_branch_id' => $originBranchId,
                'sent_by' => $userId,
                'sent_at' => Carbon::parse($sentAt),
                'invoice_number' => $invoiceNumber,
                'defect_description' => $defectDescription,
                'observation' => $observation,
                'status' => MaintenanceShipmentStatus::SENT,
            ]);

            /*
             * Coloca o equipamento em reparo externo.
             */
            $equipment->update([
                'operational_status' => OperationalStatus::EXTERNAL_REPAIR,
            ]);

            return $shipment;
        });
    }

    /**
     * Realiza um novo envio dentro de uma OS existente.
     *
     * O reenvio cria um novo ciclo de shipment, mas não cria
     * uma nova ordem de serviço.
     */
    public function resend(
        MaintenanceOrder $maintenanceOrder,
        int $companyId,
        int $originBranchId,
        string $sentAt,
        ?string $invoiceNumber,
        string $defectDescription,
        ?string $observation,
        int $userId
    ): MaintenanceShipment {
        return DB::transaction(function () use (
            $maintenanceOrder,
            $companyId,
            $originBranchId,
            $sentAt,
            $invoiceNumber,
            $defectDescription,
            $observation,
            $userId
        ) {

            /*
         * Bloqueia a OS durante a criação do novo ciclo.
         */
            $order = MaintenanceOrder::query()
                ->lockForUpdate()
                ->with([
                    'equipment',
                ])
                ->findOrFail($maintenanceOrder->id);

            /*
         * O reenvio somente pode acontecer quando
         * a OS estiver aguardando reenvio.
         */
            if (
                $order->status !== MaintenanceOrderStatus::AWAITING_RESEND
            ) {
                throw new RuntimeException(
                    'A ordem de serviço não está aguardando reenvio.'
                );
            }

            /*
         * O equipamento precisa existir.
         */
            $equipment = $order->equipment;

            if (! $equipment) {
                throw new RuntimeException(
                    'A ordem de serviço não possui um equipamento relacionado.'
                );
            }

            /*
         * Equipamentos inativos não podem ser reenviados.
         */
            if (! $equipment->active) {
                throw new RuntimeException(
                    'O equipamento está inativo e não pode ser reenviado.'
                );
            }

            /*
         * Equipamentos descartados não podem voltar ao processo.
         */
            if (
                $equipment->operational_status === OperationalStatus::DISCARDED
            ) {
                throw new RuntimeException(
                    'O equipamento está descartado e não pode ser reenviado.'
                );
            }

            /*
         * Busca o último ciclo da OS.
         */
            $lastShipment = MaintenanceShipment::query()
                ->where(
                    'maintenance_order_id',
                    $order->id
                )
                ->lockForUpdate()
                ->orderByDesc('sequence')
                ->first();

            /*
         * Uma OS aguardando reenvio precisa possuir
         * pelo menos um envio anterior.
         */
            if (! $lastShipment) {
                throw new RuntimeException(
                    'A ordem de serviço não possui um envio anterior para realizar o reenvio.'
                );
            }

            /*
         * O último ciclo precisa ter sido recebido.
         *
         * Isso impede criar um novo envio enquanto
         * o equipamento ainda estiver com a terceirizada.
         */
            if (
                $lastShipment->status !==
                MaintenanceShipmentStatus::RETURNED
            ) {
                throw new RuntimeException(
                    'O último envio ainda não foi recebido. O equipamento não está disponível para reenvio.'
                );
            }

            /*
         * O novo ciclo recebe o próximo número sequencial.
         */
            $nextSequence = $lastShipment->sequence + 1;

            /*
         * Cria o novo envio dentro da mesma OS.
         */
            $shipment = MaintenanceShipment::create([
                'maintenance_order_id' => $order->id,
                'sequence' => $nextSequence,
                'company_id' => $companyId,
                'origin_branch_id' => $originBranchId,
                'sent_by' => $userId,
                'sent_at' => Carbon::parse($sentAt),
                'invoice_number' => $invoiceNumber,
                'defect_description' => $defectDescription,
                'observation' => $observation,
                'status' => MaintenanceShipmentStatus::SENT,
            ]);

            /*
         * O novo ciclo foi iniciado.
         *
         * A OS volta para reparo externo.
         */
            $order->update([
                'status' => MaintenanceOrderStatus::IN_REPAIR,
            ]);

            /*
         * O equipamento continua em reparo externo.
         */
            $equipment->update([
                'operational_status' =>
                OperationalStatus::EXTERNAL_REPAIR,
            ]);

            return $shipment->fresh([
                'maintenanceOrder',
                'maintenanceOrder.equipment',
                'company',
                'originBranch',
                'sender',
            ]);
        });
    }

    /**
 * Atualiza os dados logísticos de um envio.
 *
 * Permite corrigir somente a filial de origem e a nota fiscal
 * enquanto o envio ainda estiver na etapa de SENT.
 */
public function updateLogistics(
    MaintenanceShipment $maintenanceShipment,
    int $originBranchId,
    ?string $invoiceNumber
): MaintenanceShipment {
    return DB::transaction(function () use (
        $maintenanceShipment,
        $originBranchId,
        $invoiceNumber
    ) {

        /*
         * Bloqueia o shipment durante a atualização.
         *
         * Isso evita alterações concorrentes no mesmo envio.
         */
        $shipment = MaintenanceShipment::query()
            ->lockForUpdate()
            ->findOrFail(
                $maintenanceShipment->id
            );

        /*
         * O envio somente pode ser alterado enquanto
         * ainda estiver aguardando o recebimento.
         */
        if (
            $shipment->status
            !== MaintenanceShipmentStatus::SENT
        ) {
            throw new RuntimeException(
                'Este envio não pode mais ter seus dados logísticos alterados.'
            );
        }

        /*
         * Atualiza somente os campos permitidos
         * nesta operação.
         */
        $shipment->update([
            'origin_branch_id' => $originBranchId,
            'invoice_number' => $invoiceNumber,
        ]);

        return $shipment->fresh([
            'maintenanceOrder',
            'company',
            'originBranch',
            'sender',
        ]);
    });
}

}

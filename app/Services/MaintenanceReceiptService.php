<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\MaintenanceOrderStatus;
use App\Enums\MaintenanceShipmentStatus;
use App\Models\MaintenanceReceipt;
use App\Models\MaintenanceShipment;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class MaintenanceReceiptService
{
    /**
     * Registra o recebimento físico de um equipamento.
     */
    public function create(
        MaintenanceShipment $maintenanceShipment,
        ?string $invoiceNumber,
        ?string $receivingObservation,
        int $userId,
        int $receivingBranchId,
    ): MaintenanceReceipt {
        return $this->receiveShipment(
            shipmentId: $maintenanceShipment->id,
            invoiceNumber: $invoiceNumber,
            receivingObservation: $receivingObservation,
            userId: $userId,
            receivingBranchId: $receivingBranchId,
        );
    }

    /**
     * Registra o recebimento físico de vários equipamentos.
     *
     * Cada equipamento é processado de forma independente.
     *
     * Uma falha em um equipamento não interfere
     * nos demais equipamentos do lote.
     */
    public function createMultiple(
    array $shipmentIds,
    ?string $invoiceNumber,
    ?string $receivingObservation,
    int $userId,
    int $receivingBranchId
): array {
    $results = [
        'success' => [],
        'errors' => [],
    ];

    /*
     * Remove IDs duplicados e normaliza os valores
     * recebidos via HTTP para inteiros.
     *
     * O Form Request valida "integer" e "distinct",
     * mas os valores enviados pelo navegador chegam
     * originalmente como strings.
     */
    $shipmentIds = array_values(
        array_unique(
            array_map(
                'intval',
                $shipmentIds
            )
        )
    );

    foreach ($shipmentIds as $shipmentId) {

        try {

            $receipt = $this->receiveShipment(
                shipmentId: $shipmentId,
                invoiceNumber: $invoiceNumber,
                receivingObservation: $receivingObservation,
                userId: $userId,
                receivingBranchId: $receivingBranchId,
            );

            $results['success'][] = [
                'shipment_id' =>
                    $shipmentId,

                'receipt' =>
                    $receipt,
            ];

        } catch (RuntimeException $exception) {

            /*
             * A falha pertence somente a este equipamento.
             *
             * O próximo shipment continuará sendo processado.
             */
            $results['errors'][] = [
                'shipment_id' =>
                    $shipmentId,

                'message' =>
                    $exception->getMessage(),
            ];
        }
    }

    return $results;
}
    /**
     * Atualiza os dados logísticos de um recebimento.
     *
     * Permite corrigir somente a filial de recebimento
     * e a nota fiscal.
     */
    public function updateLogistics(
        MaintenanceReceipt $maintenanceReceipt,
        int $receivingBranchId,
        ?string $invoiceNumber
    ): MaintenanceReceipt {
        return DB::transaction(function () use (
            $maintenanceReceipt,
            $receivingBranchId,
            $invoiceNumber
        ) {

            /*
         * Bloqueia o recebimento durante a atualização.
         *
         * Isso evita alterações concorrentes no mesmo registro.
         */
            $receipt = MaintenanceReceipt::query()
                ->lockForUpdate()
                ->findOrFail(
                    $maintenanceReceipt->id
                );

            /*
         * Atualiza somente os campos permitidos
         * nesta operação.
         */
            $receipt->update([
                'receiving_branch_id' =>
                $receivingBranchId,

                'invoice_number' =>
                $invoiceNumber,
            ]);

            return $receipt->fresh([
                'maintenanceShipment',
                'maintenanceShipment.maintenanceOrder',
                'receiver',
                'receivingBranch',
            ]);
        });
    }
    /**
     * Executa o recebimento de um único shipment.
     *
     * Este método concentra a regra de negócio utilizada
     * tanto pelo recebimento individual quanto pelo múltiplo.
     */
    private function receiveShipment(
        int $shipmentId,
        ?string $invoiceNumber,
        ?string $receivingObservation,
        int $userId,
        int $receivingBranchId
    ): MaintenanceReceipt {
        return DB::transaction(function () use (
            $shipmentId,
            $invoiceNumber,
            $receivingObservation,
            $userId,
            $receivingBranchId,
        ) {

            /*
             * Bloqueia o shipment durante o recebimento.
             *
             * Isso evita que dois processos tentem receber
             * o mesmo equipamento simultaneamente.
             */
            $shipment = MaintenanceShipment::query()
                ->lockForUpdate()
                ->with([
                    'maintenanceOrder',
                ])
                ->find(
                    $shipmentId
                );

            /*
             * O shipment precisa existir.
             */
            if (! $shipment) {
                throw new RuntimeException(
                    'O envio não foi encontrado.'
                );
            }

            /*
             * O equipamento somente pode ser recebido
             * quando o envio estiver como SENT.
             */
            if (
                $shipment->status
                !== MaintenanceShipmentStatus::SENT
            ) {
                throw new RuntimeException(
                    'Este envio não está disponível para recebimento.'
                );
            }

            /*
             * Um envio somente pode possuir um recebimento.
             */
            if (
                $shipment->receipt()->exists()
            ) {
                throw new RuntimeException(
                    'Este envio já possui um recebimento registrado.'
                );
            }

            /*
             * A OS precisa existir.
             */
            $maintenanceOrder =
                $shipment->maintenanceOrder;

            if (! $maintenanceOrder) {
                throw new RuntimeException(
                    'O envio não possui uma ordem de serviço relacionada.'
                );
            }

            /*
             * Registra o recebimento físico.
             */
            $receipt = MaintenanceReceipt::create([
                'maintenance_shipment_id' =>
                $shipment->id,

                'received_by' =>
                $userId,

                'received_at' =>
                now(),

                'receiving_branch_id' =>
                $receivingBranchId,

                'invoice_number' =>
                $invoiceNumber,

                'receiving_observation' =>
                $receivingObservation,
            ]);

            /*
             * O envio passa para RETURNED porque
             * o equipamento retornou fisicamente.
             */
            $shipment->update([
                'status' =>
                MaintenanceShipmentStatus::RETURNED,
            ]);

            /*
             * A OS passa a aguardar a avaliação técnica.
             */
            $maintenanceOrder->update([
                'status' =>
                MaintenanceOrderStatus::IN_VALIDATION,
            ]);

            return $receipt->fresh([
                'maintenanceShipment',
                'maintenanceShipment.maintenanceOrder',
                'receiver',
                'receivingBranch',
            ]);
        });
    }
}

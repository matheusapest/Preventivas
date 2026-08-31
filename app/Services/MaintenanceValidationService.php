<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\MaintenanceOrderStatus;
use App\Enums\MaintenanceValidationStatus;
use App\Enums\OperationalStatus;
use App\Models\Maintenance\MaintenanceReceipt;
use App\Models\Maintenance\MaintenanceValidation;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class MaintenanceValidationService
{
    /**
     * Registra uma nova validação técnica para um recebimento.
     */
    public function completeValidation(
        MaintenanceReceipt $maintenanceReceipt,
        MaintenanceValidationStatus $validationStatus,
        ?string $testsPerformed,
        ?string $validationObservation,
        bool $closeWithoutResend,
        int $userId
    ): MaintenanceValidation {
        return DB::transaction(function () use (
            $maintenanceReceipt,
            $validationStatus,
            $testsPerformed,
            $validationObservation,
            $closeWithoutResend,
            $userId
        ) {

            /*
             * Bloqueia o recebimento durante a operação.
             */
            $receipt = MaintenanceReceipt::query()
                ->lockForUpdate()
                ->with([
                    'maintenanceShipment.maintenanceOrder.equipment',
                ])
                ->findOrFail($maintenanceReceipt->id);

            /*
             * O recebimento precisa estar relacionado a um envio.
             */
            $shipment = $receipt->maintenanceShipment;

            if (! $shipment) {
                throw new RuntimeException(
                    'O recebimento não possui um envio relacionado.'
                );
            }

            /*
             * A OS precisa existir.
             */
            $maintenanceOrder = $shipment->maintenanceOrder;

            if (! $maintenanceOrder) {
                throw new RuntimeException(
                    'O envio não possui uma ordem de serviço relacionada.'
                );
            }

            /*
             * A validação somente pode ocorrer quando
             * a OS estiver aguardando validação.
             */
            if (
                $maintenanceOrder->status
                !== MaintenanceOrderStatus::IN_VALIDATION
            ) {
                throw new RuntimeException(
                    'A ordem de serviço não está aguardando validação.'
                );
            }

            /*
             * O equipamento precisa existir.
             */
            $equipment = $maintenanceOrder->equipment;

            if (! $equipment) {
                throw new RuntimeException(
                    'A ordem de serviço não possui um equipamento relacionado.'
                );
            }

            /*
             * Verifica se este recebimento já possui uma
             * validação conclusiva.
             *
             * REJECTED não encerra o ciclo e, portanto,
             * permite uma nova validação posteriormente.
             */
            $hasTerminalValidation = $receipt->validations()
                ->whereIn('validation_status', [
                    MaintenanceValidationStatus::APPROVED->value,
                    MaintenanceValidationStatus::NO_REPAIR->value,
                ])
                ->exists();

            if ($hasTerminalValidation) {
                throw new RuntimeException(
                    'Este ciclo de reparo já foi finalizado e não pode receber uma nova validação.'
                );
            }

            /*
             * A opção "Não reenviar" somente pode ser utilizada
             * quando o reparo foi reprovado.
             */
            if (
                $closeWithoutResend
                && $validationStatus !== MaintenanceValidationStatus::REJECTED
            ) {
                throw new RuntimeException(
                    'A opção de não reenviar somente pode ser utilizada quando o reparo for reprovado.'
                );
            }

            /*
             * Quando o técnico decide não reenviar o equipamento,
             * uma justificativa é obrigatória.
             */
            if (
                $closeWithoutResend
                && blank($validationObservation)
            ) {
                throw new RuntimeException(
                    'Informe o motivo pelo qual o equipamento não será reenviado.'
                );
            }

            /*
             * Cria o registro da validação.
             *
             * Cada avaliação técnica é preservada como
             * um evento independente no histórico.
             */
            $maintenanceValidation = MaintenanceValidation::query()->create([
                'maintenance_receipt_id' =>
                    $receipt->id,

                'validated_by' =>
                    $userId,

                'validated_at' =>
                    now(),

                'validation_status' =>
                    $validationStatus,

                'tests_performed' =>
                    $testsPerformed,

                'validation_observation' =>
                    $validationObservation,

                'close_without_resend' =>
                    $closeWithoutResend,
            ]);

            /*
             * Determina o resultado operacional da validação.
             */
            switch ($validationStatus) {

                /*
                 * Reparo aprovado.
                 *
                 * O equipamento volta para Kit Backup
                 * e a OS é finalizada.
                 */
                case MaintenanceValidationStatus::APPROVED:

                    $equipment->update([
                        'operational_status' =>
                            OperationalStatus::KIT_BACKUP,
                    ]);

                    $maintenanceOrder->update([
                        'status' =>
                            MaintenanceOrderStatus::COMPLETED,
                    ]);

                    break;


                /*
                 * Reparo reprovado.
                 */
                case MaintenanceValidationStatus::REJECTED:

                    $equipment->update([
                        'operational_status' =>
                            OperationalStatus::EXTERNAL_REPAIR,
                    ]);

                    /*
                     * O técnico decidiu não iniciar um novo envio
                     * neste momento.
                     *
                     * A OS permanece em validação porque o
                     * equipamento poderá ser reparado localmente
                     * e submetido a uma nova validação.
                     */
                    if ($closeWithoutResend) {

                        $maintenanceOrder->update([
                            'status' =>
                                MaintenanceOrderStatus::IN_VALIDATION,
                        ]);

                    /*
                     * O reparo foi reprovado e o técnico não marcou
                     * "não reenviar".
                     *
                     * O novo envio ainda NÃO foi criado.
                     *
                     * A OS fica aguardando o reenvio.
                     */
                    } else {

                        $maintenanceOrder->update([
                            'status' =>
                                MaintenanceOrderStatus::AWAITING_RESEND,
                        ]);
                    }

                    break;


                /*
                 * Equipamento sem possibilidade de conserto.
                 *
                 * O equipamento é descartado e a OS finalizada.
                 */
                case MaintenanceValidationStatus::NO_REPAIR:

                    $equipment->update([
                        'operational_status' =>
                            OperationalStatus::DISCARDED,
                    ]);

                    $maintenanceOrder->update([
                        'status' =>
                            MaintenanceOrderStatus::COMPLETED,
                    ]);

                    break;
            }

            return $maintenanceValidation->fresh([
                'maintenanceReceipt',
                'validator',
            ]);
        });
    }
}

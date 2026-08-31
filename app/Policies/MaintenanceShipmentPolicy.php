<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\MaintenanceOrderStatus;
use App\Enums\MaintenanceShipmentStatus;
use App\Models\Maintenance\MaintenanceShipment;
use App\Models\User;

class MaintenanceShipmentPolicy
{
    /**
     * Listar envios.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Visualizar um envio de equipamento.
     */
    public function view(
        User $user,
        MaintenanceShipment $maintenanceShipment
    ): bool {
        return true;
    }

    /**
     * Criar o primeiro envio de uma nova OS.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Receber fisicamente um equipamento enviado para reparo.
     */
    public function receive(
        User $user,
        MaintenanceShipment $maintenanceShipment
    ): bool {
        return true;
    }

    /**
     * Autoriza o acesso ao recebimento múltiplo.
     */
    public function receiveMultiple(User $user): bool
    {
        return true;
    }

    /**
     * Autoriza a edição dos dados logísticos do envio.
     *
     * Somente o técnico responsável pelo envio pode editar
     * a filial de origem e a nota fiscal enquanto o envio
     * ainda estiver na etapa de envio.
     */
    public function updateLogistics(
        User $user,
        MaintenanceShipment $maintenanceShipment
    ): bool {
        /*
         * O envio somente pode ser alterado enquanto
         * ainda estiver aguardando o recebimento.
         */
        if (
            $maintenanceShipment->status
            !== MaintenanceShipmentStatus::SENT
        ) {
            return false;
        }

        /*
         * Somente o usuário responsável pelo envio
         * pode corrigir os dados logísticos.
         */
        return $maintenanceShipment->sender?->id === $user->id;
    }

    /**
     * Autoriza o reenvio de um equipamento dentro
     * de uma OS existente.
     */
    public function resend(
        User $user,
        MaintenanceShipment $maintenanceShipment
    ): bool {
        /*
         * O reenvio somente pode ser iniciado quando
         * a OS estiver aguardando reenvio.
         */
        if (
            $maintenanceShipment->maintenanceOrder?->status
            !== MaintenanceOrderStatus::AWAITING_RESEND
        ) {
            return false;
        }

        /*
         * O último ciclo precisa ter sido recebido.
         */
        if (
            $maintenanceShipment->status
            !== MaintenanceShipmentStatus::RETURNED
        ) {
            return false;
        }

        return true;
    }
}

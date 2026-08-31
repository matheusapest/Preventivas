<?php

declare(strict_types=1);

namespace App\Policies\Maintenance;

use App\Models\Maintenance\MaintenanceReceipt;
use App\Models\Maintenance\MaintenanceShipment;
use App\Models\Access\User;

class MaintenanceReceiptPolicy
{
    /**
     * Autoriza o registro do recebimento de um envio.
     */
    public function receive(
        User $user,
        MaintenanceShipment $maintenanceShipment
    ): bool {
        return $maintenanceShipment->receipt === null;
    }

    /**
     * Autoriza a visualização de um recebimento.
     */
    public function view(
        User $user,
        MaintenanceReceipt $maintenanceReceipt
    ): bool {
        return true;
    }

    /**
     * Autoriza a edição dos dados logísticos do recebimento.
     *
     * Somente o usuário que realizou o recebimento pode
     * corrigir a filial de recebimento e a nota fiscal.
     */
    public function updateLogistics(
        User $user,
        MaintenanceReceipt $maintenanceReceipt
    ): bool {
        /*
         * Somente o usuário responsável pelo recebimento
         * pode editar seus dados logísticos.
         */
        return $maintenanceReceipt->received_by === $user->id;
    }
}

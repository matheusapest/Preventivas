<?php

namespace App\Policies;

use App\Models\Maintenance\MaintenanceOrder;
use App\Models\User;

class MaintenanceOrderPolicy
{
    /**
     * Listar ordem de serviços
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Visualizar a ordem de serviço
     */
    public function view(
        User $user,
        MaintenanceOrder $maintenanceOrder
    ): bool {
        return true;
    }

    /**
     * Criar uma ordem de serviço
     */
    public function create(User $user): bool
    {
        return true;
    }
}

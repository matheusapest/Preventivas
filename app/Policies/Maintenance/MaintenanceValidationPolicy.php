<?php

declare(strict_types=1);

namespace App\Policies\Maintenance;

use App\Models\Maintenance\MaintenanceValidation;
use App\Models\Access\User;

class MaintenanceValidationPolicy
{
    /**
     * Autoriza a criação de uma nova validação técnica.
     */
    public function create(
        User $user
    ): bool {
        return true;
    }

    /**
     * Autoriza a visualização de uma validação.
     */
    public function view(
        User $user,
        MaintenanceValidation $maintenanceValidation
    ): bool {
        return true;
    }
}

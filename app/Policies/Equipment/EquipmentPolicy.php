<?php

namespace App\Policies\Equipment;

use App\Models\Equipment\Equipment;
use App\Models\Access\User;
use Illuminate\Auth\Access\Response;

class EquipmentPolicy
{
    /**
     * Listar equipamentos.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Visualizar um equipamento.
     */
    public function view(
        User $user,
        Equipment $equipment
    ): bool {
        return true;
    }

    /**
     * Criar um equipamento.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Editar um equipamento.
     */
    public function update(
        User $user,
        Equipment $equipment
    ): bool {
        return true;
    }

    /**
     * Ativar/Inativar um equipamento.
     */
    public function toggleActive(
        User $user,
        Equipment $equipment
    ): Response {
        if (! $user->isAdmin()) {
            return Response::deny(__('authorization.denied'));
        }

        return Response::allow();
    }
}

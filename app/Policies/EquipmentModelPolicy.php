<?php

namespace App\Policies;

use App\Models\EquipmentModel;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EquipmentModelPolicy
{
    /**
     * Listar modelos de equipamentos.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Visualizar modelo de equipamento.
     */
    public function view(
        User $user,
        EquipmentModel $equipmentModel
    ): bool {

        return true;
    }

    /**
     * Criar um modelo de equipamento.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Editar um modelo de equipamento.
     */
    public function update(
        User $user,
        EquipmentModel $equipmentModel
    ): bool {

        return $user->isAdmin();
    }

    /**
     * Ativar/Inativar um modelo de equipamento.
     */
    public function toggleActive(
        User $user,
        EquipmentModel $equipmentModel
    ): Response {

        if (! $user->isAdmin()) {
            return Response::deny(__('authorization.denied'));
        }

        return Response::allow();
    }
}

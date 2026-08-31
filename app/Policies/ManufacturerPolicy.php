<?php

namespace App\Policies;

use App\Models\Manufacturer;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ManufacturerPolicy
{
    /**
     * Listar fabricantes.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Visualizar um fabricante.
     */
    public function view(
        User $user,
        Manufacturer $manufacturer
    ): bool {

        return true;
    }

    /**
     * Criar um fabricante.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Editar um fabricante.
     */
    public function update(
        User $user,
        Manufacturer $manufacturer
    ): bool {

        return $user->isAdmin();
    }

    /**
     * Ativar/Inativar um fabricante.
     */
    public function toggleActive(
        User $user,
        Manufacturer $manufacturer
    ): Response {

        if (! $user->isAdmin()) {
            return Response::deny(__('authorization.denied'));
        }

        return Response::allow();
    }
}

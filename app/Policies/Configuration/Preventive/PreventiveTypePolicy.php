<?php

namespace App\Policies\Configuration\Preventive;

use App\Models\PreventiveType;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PreventiveTypePolicy
{
    /**
     * Listar tipos de preventiva.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Visualizar um tipo de preventiva.
     */
    public function view(
        User $user,
        PreventiveType $preventiveType
    ): bool {
        return true;
    }

    /**
     * Criar um tipo de preventiva.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Editar um tipo de preventiva.
     */
    public function update(
        User $user,
        PreventiveType $preventiveType
    ): bool {
        return $user->isAdmin();
    }

    /**
     * Ativar/Inativar um tipo de preventiva.
     */
    public function toggleActive(
        User $user,
        PreventiveType $preventiveType
    ): Response {
        if (! $user->isAdmin()) {
            return Response::deny(__('authorization.denied'));
        }

        return Response::allow();
    }
}

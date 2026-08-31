<?php

namespace App\Policies;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BranchPolicy
{
    /**
     * Listar filiais.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Visualizar uma filial.
     */
    public function view(
        User $user,
        Branch $branch
    ): bool {

        return true;
    }

    /**
     * Criar uma filial.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Editar uma filial.
     */
    public function update(
        User $user,
        Branch $branch
    ): bool {

        return $user->isAdmin();
    }

    /**
     * Ativar/Inativar uma filial.
     */
    public function toggleActive(
        User $user,
        Branch $branch
    ): Response {

        if (! $user->isAdmin()) {
            return Response::deny(__('authorization.denied'));
        }

        return Response::allow();
    }
}

<?php

namespace App\Policies;

use App\Models\Organization\BranchCode;
use App\Models\Access\User;
use Illuminate\Auth\Access\Response;

class BranchCodePolicy
{
    /**
     * Listar códigos de filiais.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Visualizar um código de filial.
     */
    public function view(
        User $user,
        BranchCode $branchCode
    ): bool {

        return true;
    }

    /**
     * Criar um código de filial.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Editar um código de filial.
     */
    public function update(
        User $user,
        BranchCode $branchCode
    ): bool {

        return $user->isAdmin();
    }

    /**
     * Ativar/Inativar um código de filial.
     */
    public function toggleActive(
        User $user,
        BranchCode $branchCode
    ): Response {

        if (! $user->isAdmin()) {
            return Response::deny(__('authorization.denied'));
        }

        return Response::allow();
    }
}

<?php

namespace App\Policies;

use App\Models\Organization\Company;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CompanyPolicy
{
    /**
     * Listar empresas.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Visualizar empresa.
     */
    public function view(
        User $user,
        Company $company
    ): bool {
        return true;
    }

    /**
     * Criar empresa.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Editar empresa.
     */
    public function update(
        User $user,
        Company $company
    ): bool {
        return $user->isAdmin();
    }

    /**
     * Ativar/Inativar empresa.
     */
    public function toggleActive(
        User $user,
        Company $company
    ): Response {
        if (! $user->isAdmin()) {
            return Response::deny(__('authorization.denied'));
        }

        return Response::allow();
    }
}

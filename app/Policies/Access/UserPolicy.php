<?php

namespace App\Policies\Access;

use App\Models\Access\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Listar usuários.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Visualizar usuário.
     */
    public function view(User $authUser, User $user): bool
    {
        return $authUser->isAdmin()
            || $authUser->is($user);
    }

    /**
     * Criar usuário.
     */
    public function create(User $authUser): bool
    {
        return $authUser->isAdmin();
    }

    /**
     * Editar usuário.
     */
    public function update(User $authUser, User $user): bool
    {
        return $authUser->isAdmin()
            || $authUser->is($user);
    }

    /**
     * Ativar/Inativar usuário.
     */
    public function toggleActive(
        User $authUser,
        User $user
    ): Response {

        if (! $authUser->isAdmin()) {
            return Response::deny(__('authorization.denied'));
        }

        if ($authUser->is($user)) {
            return Response::deny(__('authorization.denied'));
        }

        return Response::allow();
    }

    /**
     * Permite alterar o perfil (Role) de um usuário.
     */
    public function updateRole(
        User $authUser,
        User $user
    ): bool {

        return $authUser->isAdmin();
    }
}

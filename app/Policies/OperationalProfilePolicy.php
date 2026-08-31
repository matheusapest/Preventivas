<?php

namespace App\Policies;

use App\Models\OperationalProfile;
use App\Models\User;

class OperationalProfilePolicy
{
    /**
     * Determina se o usuário pode visualizar a lista de perfis.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determina se o usuário pode visualizar um perfil.
     */
    public function view(User $user, OperationalProfile $operationalProfile): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determina se o usuário pode criar um perfil.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determina se o usuário pode atualizar um perfil.
     */
    public function update(User $user, OperationalProfile $operationalProfile): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determina se o usuário pode inativar/ativar um perfil.
     */
    public function toggleActive(User $user, OperationalProfile $operationalProfile): bool
    {
        return $user->isAdmin();
    }
}

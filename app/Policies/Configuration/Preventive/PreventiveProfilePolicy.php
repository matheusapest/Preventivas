<?php

declare(strict_types=1);

namespace App\Policies\Configuration\Preventive;

use App\Models\PreventiveProfile;
use App\Models\User;

class PreventiveProfilePolicy
{
    /**
     * Determina se o usuário pode visualizar a lista de perfis.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determina se o usuário pode visualizar um perfil específico.
     */
    public function view(
        User $user,
        PreventiveProfile $preventiveProfile
    ): bool {
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
     * Determina se o usuário pode alterar um perfil.
     */
    public function update(
        User $user,
        PreventiveProfile $preventiveProfile
    ): bool {
        return $user->isAdmin();
    }

    /**
     * Determina se o usuário pode ativar ou inativar um perfil.
     */
    public function toggleActive(
        User $user,
        PreventiveProfile $preventiveProfile
    ): bool {
        return $user->isAdmin();
    }
}

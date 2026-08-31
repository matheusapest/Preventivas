<?php

namespace App\Policies;

use App\Models\Configuration\Operational\UnitType;
use App\Models\Access\User;

class UnitTypePolicy
{
    /**
     * Determina se o usuário pode visualizar os tipos de unidade.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determina se o usuário pode visualizar um tipo de unidade.
     */
    public function view(User $user, UnitType $unitType): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determina se o usuário pode criar tipos de unidade.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determina se o usuário pode atualizar um tipo de unidade.
     */
    public function update(User $user, UnitType $unitType): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determina se o usuário pode ativar um tipo de unidade.
     */
    public function activate(User $user, UnitType $unitType): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determina se o usuário pode inativar um tipo de unidade.
     */
    public function deactivate(User $user, UnitType $unitType): bool
    {
        return $user->isAdmin();
    }
}

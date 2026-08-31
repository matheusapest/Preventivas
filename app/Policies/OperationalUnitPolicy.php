<?php

namespace App\Policies;

use App\Models\OperationalUnit;
use App\Models\User;

class OperationalUnitPolicy
{
    /**
     * Determina se o usuário pode visualizar a lista
     * de unidades operacionais.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determina se o usuário pode visualizar uma
     * unidade operacional específica.
     */
    public function view(User $user, OperationalUnit $operationalUnit): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determina se o usuário pode criar unidades operacionais.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determina se o usuário pode atualizar uma
     * unidade operacional.
     */
    public function update(
        User $user,
        OperationalUnit $operationalUnit
    ): bool {
        return $user->isAdmin();
    }

    /**
     * Determina se o usuário pode ativar ou inativar
     * uma unidade operacional.
     */
    public function toggleActive(
        User $user,
        OperationalUnit $operationalUnit
    ): bool {
        return $user->isAdmin();
    }
}

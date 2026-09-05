<?php

namespace App\Policies\Configuration\Preventive;

use App\Models\Configuration\Preventive\Activity;
use App\Models\Access\User;

class ActivityPolicy
{
    /**
     * Listar atividades.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Visualizar uma atividade.
     */
    public function view(
        User $user,
        Activity $activity
    ): bool {
        return $user->isAdmin();
    }

    /**
     * Criar uma atividade.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Editar uma atividade.
     */
    public function update(
        User $user,
        Activity $activity
    ): bool {
        return $user->isAdmin();
    }

    /**
     * Ativar/Inativar uma atividade.
     */
    public function toggleActive(
        User $user,
        Activity $activity
    ): bool {
        return $user->isAdmin();
    }
}

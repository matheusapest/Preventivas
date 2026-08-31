<?php

namespace App\Policies\Preventive;

use App\Models\Preventive\Preventive;
use App\Models\User;

class PreventiveExecutionPolicy
{
    /**
     * Determina se o usuário pode visualizar
     * o fluxo de execução da preventiva.
     */
    public function view(
        User $user,
        Preventive $preventive
    ): bool {
        return $user->isAdmin()
            || $preventive->assigned_user_id === $user->id;
    }

    /**
     * Determina se o usuário pode executar
     * atividades da preventiva.
     */
    public function execute(
        User $user,
        Preventive $preventive
    ): bool {
        return $user->isAdmin()
            || $preventive->assigned_user_id === $user->id;
    }
}

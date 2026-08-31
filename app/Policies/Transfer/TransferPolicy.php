<?php

namespace App\Policies\Transfer;

use App\Models\Equipment\Transfer;
use App\Models\Access\User;
use Illuminate\Auth\Access\Response;

class TransferPolicy
{
    /**
     * Listar transferências.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Visualizar uma transferência.
     */
    public function view(
        User $user,
        Transfer $transfer
    ): bool {
        return true;
    }

    /**
     * Criar uma nova transferência (envio).
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Confirmar o recebimento de uma transferência.
     */
    public function receive(
        User $user,
        Transfer $transfer
    ): Response {

        if (! $user->isAdmin()) {
            return Response::deny(
                __('authorization.denied')
            );
        }

        return Response::allow();
    }
}

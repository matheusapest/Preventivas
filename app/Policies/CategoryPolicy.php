<?php

namespace App\Policies;

use App\Models\Configuration\Operational\Category;
use App\Models\Access\User;
use Illuminate\Auth\Access\Response;

class CategoryPolicy
{
    /**
     * Listar categorias.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Visualizar uma categoria.
     */
    public function view(
        User $user,
        Category $category
    ): bool {

        return true;
    }

    /**
     * Criar uma categoria.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Editar uma categoria.
     */
    public function update(
        User $user,
        Category $category
    ): bool {

        return $user->isAdmin();
    }

    /**
     * Ativar/Inativar uma categoria.
     */
    public function toggleActive(
        User $user,
        Category $category
    ): Response {

        if (! $user->isAdmin()) {
            return Response::deny(__('authorization.denied'));
        }

        return Response::allow();
    }
}

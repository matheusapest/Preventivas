<?php

namespace App\Policies\Configuration\Preventive;

use App\Models\Configuration\Preventive\ActivityCategory;
use App\Models\Access\User;

class ActivityCategoryPolicy
{
    /**
     * Visualizar categorias.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Visualizar uma categoria.
     */
    public function view(
        User $user,
        ActivityCategory $activityCategory
    ): bool {
        return $user->isAdmin();
    }

    /**
     * Criar categoria.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Atualizar categoria.
     */
    public function update(
        User $user,
        ActivityCategory $activityCategory
    ): bool {
        return $user->isAdmin();
    }

    /**
     * Alterar status da categoria.
     */
    public function toggleActive(
        User $user,
        ActivityCategory $activityCategory
    ): bool {
        return $user->isAdmin();
    }
}

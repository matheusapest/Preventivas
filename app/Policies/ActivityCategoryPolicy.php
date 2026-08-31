<?php

namespace App\Policies;

use App\Models\Configuration\Preventive\ActivityCategory;
use App\Models\Access\User;

class ActivityCategoryPolicy
{
    /**
     * Visualizar categorias.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Visualizar uma categoria.
     */
    public function view(User $user, ActivityCategory $activityCategory): bool
    {
        return true;
    }

    /**
     * Criar categoria.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Atualizar categoria.
     */
    public function update(
        User $user,
        ActivityCategory $activityCategory
    ): bool {
        return true;
    }

    /**
     * Alterar status da categoria.
     */
    public function toggleActive(
        User $user,
        ActivityCategory $activityCategory
    ): bool {
        return true;
    }
}

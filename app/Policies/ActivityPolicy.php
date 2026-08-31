<?php

namespace App\Policies;

use App\Models\Configuration\Preventive\Activity;
use App\Models\Access\User;

class ActivityPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Activity $activity): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Activity $activity): bool
    {
        return true;
    }

    /**
     * Determine whether the user can toggle the active status.
     */
    public function toggleActive(User $user, Activity $activity): bool
    {
        return true;
    }
}

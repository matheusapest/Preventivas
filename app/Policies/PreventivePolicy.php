<?php

namespace App\Policies;

use App\Enums\CycleReviewStatusEnum;
use App\Enums\StatusPreventiveEnum;
use App\Models\Preventive;
use App\Models\User;

class PreventivePolicy
{
    /**
     * Determine whether the user can view any preventives.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the preventive.
     */
    public function view(
        User $user,
        Preventive $preventive
    ): bool {
        return true;
    }

    /**
     * Determine whether the user can create preventives.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can validate the preventive.
     *
     * A validação ocorre somente quando a preventiva
     * está aguardando validação do gestor.
     */
    public function validate(
        User $user,
        Preventive $preventive
    ): bool {
        return $preventive->status ===
            StatusPreventiveEnum::PENDING_APPROVAL;
    }

    /**
     * Determine whether the user can continue
     * a rejected preventive.
     */
    public function continue(
        User $user,
        Preventive $preventive
    ): bool {
        return $preventive->status ===
            StatusPreventiveEnum::IN_PROGRESS
            && $preventive->cycles()
                ->where(
                    'sequence',
                    $preventive->current_cycle
                )
                ->where(
                    'review_status',
                    CycleReviewStatusEnum::REJECTED
                )
                ->exists();
    }
}

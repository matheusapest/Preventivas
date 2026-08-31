<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\PreventiveProfile;
use App\Models\PreventiveProfileRule;
use Illuminate\Validation\ValidationException;

class PreventiveProfileRuleValidator
{
    /**
     * Garante que uma regra pertence ao perfil informado.
     */
    public function validateRuleBelongsToProfile(
        PreventiveProfile $profile,
        PreventiveProfileRule $rule
    ): void {
        $belongsToProfile = $rule
            ->preventiveProfileBranch()
            ->where(
                'preventive_profile_id',
                $profile->id
            )
            ->exists();

        if (!$belongsToProfile) {
            throw ValidationException::withMessages([
                'rule' =>
                    'A regra não pertence ao perfil de preventiva informado.',
            ]);
        }
    }
}

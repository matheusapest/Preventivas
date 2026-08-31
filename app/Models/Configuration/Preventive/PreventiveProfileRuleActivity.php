<?php

declare(strict_types=1);

namespace App\Models\Configuration\Preventive;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Activity;

#[Fillable([
    'preventive_profile_rule_id',
    'activity_id',
])]
class PreventiveProfileRuleActivity extends Model
{
    /**
     * Regra à qual a atividade pertence.
     */
    public function rule(): BelongsTo
    {
        return $this->belongsTo(
            PreventiveProfileRule::class,
            'preventive_profile_rule_id'
        );
    }

    /**
     * Atividade atribuída à regra.
     */
    public function activity(): BelongsTo
    {
        return $this->belongsTo(
            Activity::class,
            'activity_id'
        );
    }
}

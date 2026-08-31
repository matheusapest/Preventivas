<?php

declare(strict_types=1);

namespace App\Models\Configuration\Preventive;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\OperationalUnit;

#[Fillable([
    'preventive_profile_rule_id',
    'operational_unit_id',
])]
class PreventiveProfileRuleUnit extends Model
{
    /**
     * Regra à qual a unidade pertence.
     */
    public function rule(): BelongsTo
    {
        return $this->belongsTo(
            PreventiveProfileRule::class,
            'preventive_profile_rule_id'
        );
    }

    /**
     * Unidade operacional abrangida pela regra.
     */
    public function operationalUnit(): BelongsTo
    {
        return $this->belongsTo(
            OperationalUnit::class,
            'operational_unit_id'
        );
    }
}

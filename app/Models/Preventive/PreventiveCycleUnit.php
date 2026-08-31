<?php

declare(strict_types=1);

namespace App\Models\Preventive;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'preventive_cycle_id',
    'snapshot_unit_id',
    'operational_unit_id',
])]
class PreventiveCycleUnit extends Model
{
    protected $table = 'preventive_cycle_units';

    public function cycle(): BelongsTo
    {
        return $this->belongsTo(
            PreventiveCycle::class,
            'preventive_cycle_id'
        );
    }

    public function snapshotUnit(): BelongsTo
    {
        return $this->belongsTo(
            PreventiveSnapshotUnit::class,
            'snapshot_unit_id'
        );
    }

    public function activities(): HasMany
    {
        return $this->hasMany(
            PreventiveCycleUnitActivity::class,
            'preventive_cycle_unit_id'
        );
    }

    public function activityResponses(): HasMany
    {
        return $this->hasMany(
            PreventiveActivityResponse::class,
            'preventive_cycle_unit_id'
        );
    }
}

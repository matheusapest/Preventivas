<?php

declare(strict_types=1);

namespace App\Models\Preventive;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PreventiveCycleUnitActivity extends Model
{
    protected $table = 'preventive_cycle_unit_activities';

    protected $fillable = [
        'preventive_cycle_unit_id',
        'snapshot_rule_activity_id',
    ];

    public function cycleUnit(): BelongsTo
    {
        return $this->belongsTo(
            PreventiveCycleUnit::class,
            'preventive_cycle_unit_id'
        );
    }

    public function snapshotRuleActivity(): BelongsTo
    {
        return $this->belongsTo(
            PreventiveSnapshotRuleActivity::class,
            'snapshot_rule_activity_id'
        );
    }
}

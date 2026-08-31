<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PreventiveSnapshotRuleActivity extends Model
{
    protected $fillable = [
        'preventive_snapshot_rule_id',
        'activity_id',
        'activity_name',
        'activity_description',
        'activity_type',
    ];

    public function rule(): BelongsTo
    {
        return $this->belongsTo(
            PreventiveSnapshotRule::class,
            'preventive_snapshot_rule_id'
        );
    }

    public function responses(): HasMany
    {
        return $this->hasMany(
            PreventiveActivityResponse::class,
            'snapshot_rule_activity_id'
        );
    }
}

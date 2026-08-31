<?php

declare(strict_types=1);

namespace App\Models\Preventive;

use App\Enums\PreventiveProfileRuleType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PreventiveSnapshotRule extends Model
{
    protected $table = 'preventive_snapshot_rules';

    protected $fillable = [
        'preventive_snapshot_id',
        'preventive_profile_rule_id',
        'rule_type',
    ];

    protected $casts = [
        'rule_type' => PreventiveProfileRuleType::class,
    ];

    public function snapshot(): BelongsTo
    {
        return $this->belongsTo(
            PreventiveSnapshot::class,
            'preventive_snapshot_id'
        );
    }

    public function units(): HasMany
    {
        return $this->hasMany(
            PreventiveSnapshotRuleUnit::class,
            'preventive_snapshot_rule_id'
        );
    }

    public function activities(): HasMany
    {
        return $this->hasMany(
            PreventiveSnapshotRuleActivity::class,
            'preventive_snapshot_rule_id'
        );
    }
}

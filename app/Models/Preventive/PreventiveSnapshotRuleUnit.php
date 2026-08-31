<?php

namespace App\Models\Preventive;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PreventiveSnapshotRuleUnit extends Model
{
    protected $fillable = [
    'preventive_snapshot_rule_id',
    'operational_unit_id',
    'operational_unit_name',
    'operational_unit_identifier',
];

    public function rule(): BelongsTo
    {
        return $this->belongsTo(
            PreventiveSnapshotRule::class,
            'preventive_snapshot_rule_id'
        );
    }


}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PreventiveSnapshotUnit extends Model
{
    protected $fillable = [
        'preventive_snapshot_id',
        'operational_unit_id',
        'operational_profile_id',
        'unit_type_id',
        'operational_unit_name',
        'operational_unit_identifier',
        'operational_profile_name',
        'unit_type_name',
        'operational_composition',
    ];

    protected $casts = [
        'operational_composition' => 'array',
    ];

    /**
     * Snapshot ao qual a unidade pertence.
     */
    public function snapshot(): BelongsTo
    {
        return $this->belongsTo(
            PreventiveSnapshot::class,
            'preventive_snapshot_id'
        );
    }

    /**
     * Unidade operacional original.
     */
    public function operationalUnit(): BelongsTo
    {
        return $this->belongsTo(
            OperationalUnit::class,
            'operational_unit_id'
        );
    }

    /**
     * Perfil operacional original.
     */
    public function operationalProfile(): BelongsTo
    {
        return $this->belongsTo(
            OperationalProfile::class,
            'operational_profile_id'
        );
    }

    /**
     * Tipo da unidade original.
     */
    public function unitType(): BelongsTo
    {
        return $this->belongsTo(
            UnitType::class,
            'unit_type_id'
        );
    }
}

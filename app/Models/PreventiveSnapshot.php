<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PreventiveSnapshot extends Model
{
    protected $fillable = [
        'preventive_id',
        'preventive_type_id',
        'preventive_profile_id',
        'branch_id',
        'preventive_type_name',
        'preventive_profile_name',
        'branch_name',
    ];

    /**
     * Preventiva à qual este snapshot pertence.
     */
    public function preventive(): BelongsTo
    {
        return $this->belongsTo(Preventive::class);
    }

    /**
     * Tipo de preventiva utilizado na origem.
     */
    public function preventiveType(): BelongsTo
    {
        return $this->belongsTo(
            PreventiveType::class,
            'preventive_type_id'
        );
    }

    /**
     * Perfil utilizado como template.
     */
    public function preventiveProfile(): BelongsTo
    {
        return $this->belongsTo(
            PreventiveProfile::class,
            'preventive_profile_id'
        );
    }

    /**
     * Filial da preventiva.
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Unidades congeladas neste snapshot.
     */
    public function units(): HasMany
    {
        return $this->hasMany(
            PreventiveSnapshotUnit::class,
            'preventive_snapshot_id'
        );
    }

    /**
     * Regras congeladas neste snapshot.
     */
    public function rules(): HasMany
    {
        return $this->hasMany(
            PreventiveSnapshotRule::class,
            'preventive_snapshot_id'
        );
    }
}

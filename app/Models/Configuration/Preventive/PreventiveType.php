<?php

namespace App\Models\Configuration\Preventive;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Configuration\Preventive\Activity;
use App\Models\Configuration\Operational\UnitType;
use App\Models\Configuration\Preventive\PreventiveProfile;

#[Fillable([
    'unit_type_id',
    'name',
    'description',
    'active',
])]
class PreventiveType extends Model
{
    /**
     * Conversões automáticas de atributos.
     */
    protected function casts(): array
    {
        return [
            'active' => 'boolean',
        ];
    }

    /**
     * Tipo de unidade ao qual a preventiva pertence.
     */
    public function unitType(): BelongsTo
    {
        return $this->belongsTo(UnitType::class);
    }

    /**
     * Atividades configuradas para este tipo de preventiva.
     */
    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    /**
     * Scope para tipos de preventiva ativos.
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Scope para tipos de preventiva inativos.
     */
    public function scopeInactive($query)
    {
        return $query->where('active', false);
    }

    public function profiles(): HasMany
    {
        return $this->hasMany(PreventiveProfile::class);
    }
}

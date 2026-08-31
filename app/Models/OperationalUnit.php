<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'identifier',
    'branch_id',
    'unit_type_id',
    'operational_profile_id',
    'active',
])]
class OperationalUnit extends Model
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
     * Filial onde a unidade operacional está localizada.
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Tipo da unidade operacional.
     */
    public function unitType(): BelongsTo
    {
        return $this->belongsTo(UnitType::class);
    }

    /**
     * Perfil operacional da unidade.
     */
    public function operationalProfile(): BelongsTo
    {
        return $this->belongsTo(OperationalProfile::class);
    }

    /**
     * Scope para unidades ativas.
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Scope para unidades inativas.
     */
    public function scopeInactive($query)
    {
        return $query->where('active', false);
    }
}

<?php

namespace App\Models\Configuration\Operational;

use App\Models\Configuration\Operational\UnitType;
use App\Models\Configuration\Operational\OperationalProfileCategory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'name',
    'unit_type_id',
    'active',
])]
class OperationalProfile extends Model
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
     * Tipo de unidade ao qual o perfil pertence.
     */
    public function unitType(): BelongsTo
    {
        return $this->belongsTo(UnitType::class);
    }

    /**
     * Categorias que compõem este perfil operacional.
     */
    public function categories(): HasMany
    {
        return $this->hasMany(OperationalProfileCategory::class);
    }

    /**
     * Scope para perfis ativos.
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Scope para perfis inativos.
     */
    public function scopeInactive($query)
    {
        return $query->where('active', false);
    }
}

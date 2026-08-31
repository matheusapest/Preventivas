<?php

namespace App\Models\Configuration\Operational;

use App\Models\Configuration\Operational\OperationalProfileCategory;
use App\Models\Configuration\Operational\UnitType;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'name',
    'active',
])]
class Category extends Model
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
     * Scope para categorias ativas.
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Scope para categorias inativas.
     */
    public function scopeInactive($query)
    {
        return $query->where('active', false);
    }

    /**
     * Tipos de unidade aos quais a categoria pertence.
     *
     * Uma categoria pode ser utilizada por vários
     * tipos de unidade operacional.
     */
    public function unitTypes(): BelongsToMany
    {
        return $this->belongsToMany(UnitType::class);
    }

    /**
     * Perfis operacionais que utilizam esta categoria.
     */
    public function operationalProfileCategories(): HasMany
    {
        return $this->hasMany(OperationalProfileCategory::class);
    }


}

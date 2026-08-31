<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'name',
    'active',
])]
class UnitType extends Model
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
     * Scope para tipos ativos.
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Scope para tipos inativos.
     */
    public function scopeInactive($query)
    {
        return $query->where('active', false);
    }

    /**
     * Perfis operacionais deste tipo de unidade.
     */
    public function operationalProfiles(): HasMany
    {
        return $this->hasMany(OperationalProfile::class);
    }

    /**
     * Categorias de equipamentos disponíveis
     * para este tipo de unidade.
     *
     * Uma categoria pode pertencer a vários
     * tipos de unidade.
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    /**
     * Filiais onde este tipo de unidade está disponível.
     */
    public function branches(): BelongsToMany
    {
        return $this->belongsToMany(Branch::class);
    }

    public function preventiveTypes(): HasMany
    {
        return $this->hasMany(PreventiveType::class);
    }
}

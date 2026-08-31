<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'name',
    'active',
])]
class Manufacturer extends Model
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
     * Scope para fabricantes ativos.
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Scope para fabricantes inativos.
     */
    public function scopeInactive($query)
    {
        return $query->where('active', false);
    }
}

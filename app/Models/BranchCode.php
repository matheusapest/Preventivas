<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;


#[Fillable([
    'code',
    'description',
    'active',
])]
class BranchCode extends Model
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
     * Um código pertence a uma única filial.
     */
    public function branch(): HasOne
    {
        return $this->hasOne(Branch::class);
    }

    /**
     * Scope para códigos ativos.
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Scope para códigos inativos.
     */
    public function scopeInactive($query)
    {
        return $query->where('active', false);
    }


}

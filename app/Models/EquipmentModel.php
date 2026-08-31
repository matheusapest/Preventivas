<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'manufacturer_id',
    'category_id',
    'name',
    'active',
])]
class EquipmentModel extends Model
{
    /**
     * Tabela utilizada pela Model.
     */
    protected $table = 'models';

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
     * Modelo pertence a um fabricante.
     */
    public function manufacturer(): BelongsTo
    {
        return $this->belongsTo(Manufacturer::class);
    }

    /**
     * Modelo pertence a uma categoria.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Scope para modelos ativos.
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Scope para modelos inativos.
     */
    public function scopeInactive($query)
    {
        return $query->where('active', false);
    }
}

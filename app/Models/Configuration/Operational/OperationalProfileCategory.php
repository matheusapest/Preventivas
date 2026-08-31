<?php

namespace App\Models\Configuration\Operational;

use App\Models\Category;
use App\Models\Configuration\Operational\OperationalProfile;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'operational_profile_id',
    'category_id',
    'quantity',
])]
class OperationalProfileCategory extends Model
{
    /**
     * Conversões automáticas de atributos.
     */
    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
        ];
    }

    /**
     * Perfil operacional ao qual a composição pertence.
     */
    public function operationalProfile(): BelongsTo
    {
        return $this->belongsTo(OperationalProfile::class);
    }

    /**
     * Categoria de equipamento da composição.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}

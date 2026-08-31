<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ActivityCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'active',
    ];

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
     * Atividades que pertencem a esta categoria.
     */
    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }
}

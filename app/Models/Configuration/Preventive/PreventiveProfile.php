<?php

declare(strict_types=1);

namespace App\Models\Configuration\Preventive;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Configuration\Preventive\PreventiveType;

#[Fillable([
    'preventive_type_id',
    'name',
    'description',
    'active',
])]
class PreventiveProfile extends Model
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
     * Tipo de preventiva utilizado pelo perfil.
     */
    public function preventiveType(): BelongsTo
    {
        return $this->belongsTo(
            PreventiveType::class
        );
    }

    /**
     * Filiais participantes deste perfil.
     */
    public function branches(): HasMany
    {
        return $this->hasMany(
            PreventiveProfileBranch::class
        );
    }
}

<?php

namespace App\Models;

use App\Enums\ActivityKind;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'preventive_type_id',
    'activity_category_id',
    'name',
    'description',
    'type',
    'active',
])]
class Activity extends Model
{
    /**
     * Conversões automáticas de atributos.
     */
    protected function casts(): array
    {
        return [
            'type' => ActivityKind::class,
            'active' => 'boolean',
        ];
    }

    /**
     * Tipo de preventiva ao qual a atividade pertence.
     */
    public function preventiveType(): BelongsTo
    {
        return $this->belongsTo(PreventiveType::class);
    }

    /**
     * Categoria à qual a atividade pertence.
     */
    public function activityCategory(): BelongsTo
    {
        return $this->belongsTo(ActivityCategory::class);
    }

    /**
     * Scope para atividades ativas.
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Scope para atividades inativas.
     */
    public function scopeInactive($query)
    {
        return $query->where('active', false);
    }
}

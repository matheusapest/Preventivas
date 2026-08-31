<?php

declare(strict_types=1);

namespace App\Models\Configuration\Preventive;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Branch;

#[Fillable([
    'preventive_profile_id',
    'branch_id',
])]
class PreventiveProfileBranch extends Model
{
    /**
     * Perfil ao qual esta filial pertence.
     */
    public function preventiveProfile(): BelongsTo
    {
        return $this->belongsTo(
            PreventiveProfile::class
        );
    }

    /**
     * Filial participante do perfil.
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(
            Branch::class
        );
    }

    /**
     * Regras configuradas para esta filial.
     */
    public function rules(): HasMany
    {
        return $this->hasMany(
            PreventiveProfileRule::class,
            'preventive_profile_branch_id'
        );
    }
}

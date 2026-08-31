<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PreventiveProfileRuleType;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'preventive_profile_branch_id',
    'rule_type',
])]
class PreventiveProfileRule extends Model
{
    /**
     * Conversões automáticas de atributos.
     */
    protected function casts(): array
    {
        return [
            'rule_type' => PreventiveProfileRuleType::class,
        ];
    }

    /**
     * Filial do perfil à qual esta regra pertence.
     */
    public function preventiveProfileBranch(): BelongsTo
    {
        return $this->belongsTo(
            PreventiveProfileBranch::class,
            'preventive_profile_branch_id'
        );
    }

    /**
     * Unidades operacionais abrangidas pela regra.
     */
    public function units(): HasMany
    {
        return $this->hasMany(
            PreventiveProfileRuleUnit::class,
            'preventive_profile_rule_id'
        );
    }

    /**
     * Atividades atribuídas à regra.
     */
    public function activities(): HasMany
    {
        return $this->hasMany(
            PreventiveProfileRuleActivity::class,
            'preventive_profile_rule_id'
        );
    }
}

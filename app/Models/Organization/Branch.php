<?php

namespace App\Models\Organization;

use App\Models\Configuration\Operational\OperationalUnit;
use App\Models\Configuration\Operational\UnitType;
use App\Models\Organization\BranchCode;
use App\Models\Organization\Company;

use App\Enums\BranchType;
use App\Enums\State;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'company_id',
    'branch_code_id',
    'name',
    'city',
    'state',
    'type',
    'active',
])]
class Branch extends Model
{
    /**
     * Conversões automáticas de atributos.
     */
    protected function casts(): array
    {
        return [
            'state' => State::class,
            'type' => BranchType::class,
            'active' => 'boolean',
        ];
    }

    /**
     * Empresa proprietária da filial.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Código corporativo da filial.
     */
    public function branchCode(): BelongsTo
    {
        return $this->belongsTo(BranchCode::class);
    }

    /**
     * Scope para filiais ativas.
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Scope para filiais inativas.
     */
    public function scopeInactive($query)
    {
        return $query->where('active', false);
    }

    /**
     * Tipos de unidade disponíveis nesta filial.
     */
    public function unitTypes(): BelongsToMany
    {
        return $this->belongsToMany(UnitType::class);
    }

    /**
     * Unidades operacionais pertencentes à filial.
     */
    public function operationalUnits(): HasMany
    {
        return $this->hasMany(OperationalUnit::class);
    }
}

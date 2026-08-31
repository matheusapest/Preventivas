<?php

namespace App\Models;

use App\Enums\CompanyType;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'name',
    'type',
    'active',
])]
class Company extends Model
{
    /**
     * Tabela utilizada pela Model.
     */
    protected $table = 'companies';

    /**
     * Conversões automáticas de atributos.
     */
    protected function casts(): array
    {
        return [
            'type' => CompanyType::class,
            'active' => 'boolean',
        ];
    }

    /**
     * Uma empresa possui várias filiais.
     */
    public function branches(): HasMany
    {
        return $this->hasMany(Branch::class);
    }

    /**
     * Scope para empresas ativas.
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Scope para empresas inativas.
     */
    public function scopeInactive($query)
    {
        return $query->where('active', false);
    }

    /**
     * Scope para empresas do Grupo Empresarial.
     */
    public function scopeGroup($query)
    {
        return $query->where(
            'type',
            CompanyType::GROUP
        );
    }

    /**
     * Scope para empresas terceirizadas.
     */
    public function scopeOutsourced($query)
    {
        return $query->where(
            'type',
            CompanyType::OUTSOURCED
        );
    }

    /**
     * Indica se a empresa pertence ao Grupo Empresarial.
     */
    public function isGroup(): bool
    {
        return $this->type === CompanyType::GROUP;
    }

    /**
     * Indica se a empresa é terceirizada.
     */
    public function isOutsourced(): bool
    {
        return $this->type === CompanyType::OUTSOURCED;
    }
}

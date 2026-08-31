<?php

namespace App\Models\Equipment;

use App\Models\Organization\Branch;
use App\Models\Equipment\EquipmentModel;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\OperationalStatus;

#[Fillable([
    'branch_id',
    'equipment_model_id',
    'name',
    'asset_number',
    'serial_number',
    'internal_tag',
    'description',
    'active',
    'operational_status'
])]
class Equipment extends Model
{
    /**
     * Tabela utilizada pela Model.
     */
    protected $table = 'equipments';

    /**
     * Conversões automáticas de atributos.
     */
    protected function casts(): array
    {
        return [
            'active' => 'boolean',
            'operational_status' => OperationalStatus::class,
        ];
    }

    /**
     * Equipamento pertence a uma filial.
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Equipamento pertence a um modelo.
     */
    public function equipmentModel(): BelongsTo
    {
        return $this->belongsTo(
            EquipmentModel::class,
            'equipment_model_id'
        );
    }

    /**
     * Scope para equipamentos ativos.
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Scope para equipamentos inativos.
     */
    public function scopeInactive($query)
    {
        return $query->where('active', false);
    }
}

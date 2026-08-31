<?php

namespace App\Models\Maintenance;

use App\Enums\MaintenanceOrderStatus;
use App\Models\Equipment\Equipment;
use App\Models\Maintenance\MaintenanceShipment;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'equipment_id',
    'status',
])]
class MaintenanceOrder extends Model
{
    /**
     * Tabela utilizada pela Model.
     */
    protected $table = 'maintenance_orders';

    /**
     * Conversões automáticas de atributos.
     */
    protected function casts(): array
    {
        return [
            'status' => MaintenanceOrderStatus::class,
        ];
    }

    /**
     * Uma ordem de serviço pertence a um equipamento.
     */
    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class);
    }

    /**
     * Uma ordem de serviço possui vários envios.
     */
    public function shipments(): HasMany
    {
        return $this->hasMany(
            MaintenanceShipment::class
        );
    }
}

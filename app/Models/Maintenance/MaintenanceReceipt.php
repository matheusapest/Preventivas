<?php

declare(strict_types=1);

namespace App\Models\Maintenance;

use App\Models\Organization\Branch;
use App\Models\Maintenance\MaintenanceShipment;
use App\Models\Maintenance\MaintenanceValidation;
use App\Models\User;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'maintenance_shipment_id',
    'received_by',
    'received_at',
    'receiving_branch_id',
    'invoice_number',
    'receiving_observation',
])]
class MaintenanceReceipt extends Model
{
    /**
     * Tabela utilizada pela Model.
     */
    protected $table = 'maintenance_receipts';

    /**
     * Conversões automáticas de atributos.
     */
    protected function casts(): array
    {
        return [
            'received_at' => 'datetime',
        ];
    }

    /**
     * O recebimento pertence a um envio.
     */
    public function maintenanceShipment(): BelongsTo
    {
        return $this->belongsTo(
            MaintenanceShipment::class
        );
    }

    /**
     * Usuário responsável pelo recebimento físico.
     */
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'received_by'
        );
    }

    /**
     * Filial onde o equipamento foi recebido fisicamente.
     */
    public function receivingBranch(): BelongsTo
    {
        return $this->belongsTo(
            Branch::class,
            'receiving_branch_id'
        );
    }

    /**
     * Validações técnicas realizadas após o recebimento.
     */
    public function validations(): HasMany
    {
        return $this->hasMany(
            MaintenanceValidation::class
        );
    }
}

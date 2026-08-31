<?php

namespace App\Models\Maintenance;

use App\Enums\MaintenanceShipmentStatus;
use App\Models\Organization\Branch;
use App\Models\Organization\Company;
use App\Models\Maintenance\MaintenanceOrder;
use App\Models\Maintenance\MaintenanceReceipt;
use App\Models\Access\User;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable([
    'maintenance_order_id',
    'sequence',
    'company_id',
    'origin_branch_id',
    'sent_by',
    'sent_at',
    'invoice_number',
    'defect_description',
    'observation',
    'status',
])]
class MaintenanceShipment extends Model
{
    /**
     * Tabela utilizada pela Model.
     */
    protected $table = 'maintenance_shipments';

    /**
     * Conversões automáticas de atributos.
     */
    protected function casts(): array
    {
        return [
            'status' => MaintenanceShipmentStatus::class,
            'sent_at' => 'datetime',
        ];
    }

    /**
     * Um envio de equipamento pertence a uma ordem de serviço.
     */
    public function maintenanceOrder(): BelongsTo
    {
        return $this->belongsTo(MaintenanceOrder::class);
    }

    /**
     * Um envio pode possuir um recebimento.
     */
    public function receipt(): HasOne
    {
        return $this->hasOne(
            MaintenanceReceipt::class
        );
    }

    /**
     * O envio pertence a uma empresa terceirizada.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Um equipamento é enviado de uma filial.
     */
    public function originBranch(): BelongsTo
    {
        return $this->belongsTo(
            Branch::class,
            'origin_branch_id'
        );
    }

    /**
     * Um equipamento é enviado por um usuário.
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'sent_by'
        );
    }
}

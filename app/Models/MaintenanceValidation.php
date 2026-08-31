<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\MaintenanceValidationStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'maintenance_receipt_id',
    'validated_by',
    'validated_at',
    'validation_status',
    'tests_performed',
    'validation_observation',
    'close_without_resend',
])]
class MaintenanceValidation extends Model
{
    /**
     * Tabela utilizada pela Model.
     */
    protected $table = 'maintenance_validations';

    /**
     * Conversões automáticas de atributos.
     */
    protected function casts(): array
    {
        return [
            'validated_at' => 'datetime',
            'validation_status' => MaintenanceValidationStatus::class,
            'close_without_resend' => 'boolean',
        ];
    }

    /**
     * A validação pertence a um recebimento.
     */
    public function maintenanceReceipt(): BelongsTo
    {
        return $this->belongsTo(
            MaintenanceReceipt::class
        );
    }

    /**
     * Usuário responsável pela validação técnica.
     */
    public function validator(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'validated_by'
        );
    }
}

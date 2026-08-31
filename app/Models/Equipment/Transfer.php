<?php

namespace App\Models\Equipment;

use App\Models\Equipment\Equipment;
use App\Models\Organization\Branch;
use App\Models\Access\User;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\TransferStatus;

#[Fillable([
    'equipment_id',
    'origin_branch_id',
    'destination_branch_id',
    'sent_by',
    'sent_at',
    'received_by',
    'received_at',
    'status',
    'observation',
])]
class Transfer extends Model
{
    /**
     * Tabela utilizada pela Model.
     */
    protected $table = 'transfers';

    /**
     * Conversões automáticas de atributos.
     */
    protected function casts(): array
    {
        return [
            'sent_at' => 'datetime',
            'received_at' => 'datetime',
            'status' => TransferStatus::class,
        ];
    }

    /**
     * transferência pertence a um equipamento.
     */
    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class);
    }

    /**
     * transferência pertence a uma filial de origem.
     */
    public function originBranch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'origin_branch_id');
    }

    /**
     * transferência pertence a uma filial de destino.
     */
    public function destinationBranch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'destination_branch_id');
    }

    /**
     * transferência pertence a um usuário responsável pelo envio.
     */
    public function sentBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sent_by');
    }

    /**
     * transferência pertence a um usuário responsável pelo recebimento.
     */
    public function receivedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    /**
     * Scope para transferências enviadas.
     */
    public function scopeSent($query)
    {
        return $query->where(
            'status',
            TransferStatus::SENT
        );
    }

    /**
     * Scope para transferências recebidas.
     */
    public function scopeReceived($query)
    {
        return $query->where(
            'status',
            TransferStatus::RECEIVED
        );
    }
}

<?php

namespace App\Models\Preventive;

use App\Enums\StatusPreventiveEnum;
use App\Models\Organization\Branch;
use App\Models\Configuration\Preventive\PreventiveProfile;
use App\Models\Configuration\Preventive\PreventiveType;
use App\Models\User;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable([
    'branch_id',
    'preventive_type_id',
    'preventive_profile_id',
    'assigned_user_id',
    'created_by',
    'start_date',
    'start_at',
    'due_date',
    'status',
    'current_cycle',
    'completed_at',
    'approved_at',
    'approved_by',
])]
class Preventive extends Model
{
    /**
     * Conversões automáticas de atributos.
     */
    protected function casts(): array
    {
        return [
            'status' => StatusPreventiveEnum::class,
            'start_date' => 'date',
            'due_date' => 'date',
            'start_at' => 'datetime',
            'completed_at' => 'datetime',
            'approved_at' => 'datetime',
        ];
    }
    /**
     * Filial onde a preventiva será executada.
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Tipo de preventiva utilizado pela instância.
     */
    public function preventiveType(): BelongsTo
    {
        return $this->belongsTo(PreventiveType::class);
    }

    /**
     * Perfil utilizado como template da preventiva.
     */
    public function preventiveProfile(): BelongsTo
    {
        return $this->belongsTo(PreventiveProfile::class);
    }

    /**
     * Usuário responsável pela execução.
     */
    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    /**
     * Usuário que criou a preventiva.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Usuário que aprovou a preventiva.
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Snapshot congelado da configuração da preventiva.
     */
    public function snapshot(): HasOne
    {
        return $this->hasOne(PreventiveSnapshot::class);
    }

    /**
     * Ciclos de execução da preventiva.
     */
    public function cycles(): HasMany
    {
        return $this->hasMany(PreventiveCycle::class);
    }
}

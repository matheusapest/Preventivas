<?php

namespace App\Models\Preventive;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\PreventiveActivityFinalStatusEnum;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable([
    'preventive_cycle_unit_id',
    'snapshot_rule_activity_id',
    'result',
    'final_status',
    'observation',
    'response_data',
    'started_at',
    'answered_at',
])]
class PreventiveActivityResponse extends Model
{
    /**
     * Conversões automáticas de atributos.
     */
    protected function casts(): array
    {
        return [
            'final_status' => PreventiveActivityFinalStatusEnum::class,
            'response_data' => 'array',
            'started_at' => 'datetime',
            'answered_at' => 'datetime',
        ];
    }

    /**
     * Unidade da preventiva dentro do ciclo.
     *
     * Através desta relação conseguimos chegar ao:
     * - ciclo
     * - snapshot
     * - unidade operacional
     */
    public function cycleUnit(): BelongsTo
    {
        return $this->belongsTo(
            PreventiveCycleUnit::class,
            'preventive_cycle_unit_id'
        );
    }

    /**
     * Atividade congelada no snapshot.
     *
     * Esta é a atividade que efetivamente deveria
     * ser executada nesta preventiva.
     */
    public function snapshotRuleActivity(): BelongsTo
    {
        return $this->belongsTo(
            PreventiveSnapshotRuleActivity::class,
            'snapshot_rule_activity_id'
        );
    }

    public function photo(): HasOne
    {
        return $this->hasOne(
            PreventiveActivityResponsePhoto::class,
            'preventive_activity_response_id'
        );
    }
}

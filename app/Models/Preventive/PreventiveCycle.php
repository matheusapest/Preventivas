<?php

namespace App\Models\Preventive;

use App\Enums\StatusCycleEnum;
use App\Enums\CycleReviewStatusEnum;
use App\Models\Access\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class PreventiveCycle extends Model
{
    protected $fillable = [
        'preventive_id',
        'sequence',
        'status',
    ];
    protected function casts(): array
    {
        return [
            'status' => StatusCycleEnum::class,
            'review_status' => CycleReviewStatusEnum::class,
            'reviewed_at' => 'datetime',
        ];
    }

    public function preventive(): BelongsTo
    {
        return $this->belongsTo(Preventive::class);
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'reviewed_by'
        );
    }
    public function units(): HasMany
    {
        return $this->hasMany(
            PreventiveCycleUnit::class,
            'preventive_cycle_id'
        );
    }
}

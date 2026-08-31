<?php

declare(strict_types=1);

namespace App\Models\Preventive;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PreventiveActivityResponsePhoto extends Model
{
    protected $fillable = [
        'preventive_activity_response_id',
        'path',
        'mime_type',
        'size',
        'captured_at',
    ];

    protected function casts(): array
    {
        return [
            'size' => 'integer',
            'captured_at' => 'datetime',
        ];
    }

    public function response(): BelongsTo
    {
        return $this->belongsTo(
            PreventiveActivityResponse::class,
            'preventive_activity_response_id'
        );
    }
}

<?php

declare(strict_types=1);

namespace App\Enums;

enum PreventiveActivityFinalStatusEnum: string
{
    case OPERATIONAL = 'Operacional';
    case RESOLVED = 'resolvido';
    case PENDING = 'pendente';

    public function label(): string
    {
        return match ($this) {
            self::OPERATIONAL => 'Operacional',
            self::RESOLVED => 'Resolvido',
            self::PENDING => 'Pendente',
        };
    }

    public function colorClass(): string
    {
        return match ($this) {
            self::OPERATIONAL =>
                'bg-blue-50 text-blue-700 border-blue-200',

            self::RESOLVED =>
                'bg-green-50 text-green-700 border-green-200',

            self::PENDING =>
                'bg-amber-50 text-amber-700 border-amber-200',
        };
    }
}

<?php

declare(strict_types=1);

namespace App\Enums;

enum MaintenanceShipmentStatus: string
{
    case SENT = 'sent';
    case RETURNED = 'returned';

    public function label(): string
    {
        return match ($this) {
            self::SENT => 'Enviado',
            self::RETURNED => 'Retornado',
        };
    }
}

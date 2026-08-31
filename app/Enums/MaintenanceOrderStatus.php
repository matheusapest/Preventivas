<?php

declare(strict_types=1);

namespace App\Enums;

enum MaintenanceOrderStatus: string
{
    case IN_REPAIR = 'in_repair';
    case IN_VALIDATION = 'in_validation';
    case COMPLETED = 'completed';
    case AWAITING_RESEND = 'awaiting_resend';

    public function label(): string
    {
        return match ($this) {
            self::IN_REPAIR => 'Em reparo externo',
            self::IN_VALIDATION => 'Em validação',
            self::COMPLETED => 'Finalizada',
            self::AWAITING_RESEND => 'Aguardando Reenvio'
        };
    }
}

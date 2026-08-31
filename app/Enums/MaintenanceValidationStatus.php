<?php

declare(strict_types=1);

namespace App\Enums;

enum MaintenanceValidationStatus: string
{
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case NO_REPAIR = 'no_repair';

    public function label(): string
    {
        return match ($this) {
            self::APPROVED => 'Aprovado',
            self::REJECTED => 'Reprovado',
            self::NO_REPAIR=> 'Sem Conserto',
        };
    }
}

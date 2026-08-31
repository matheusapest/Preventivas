<?php

declare(strict_types=1);

namespace App\Enums;

enum OperationalStatus: string
{
    case KIT_BACKUP = 'kit_backup';
    case OPERATING = 'operating';
    case EXTERNAL_REPAIR = 'external_repair';
    case DISCARDED = 'discarded';

    public function label(): string
    {
        return match ($this) {
            self::KIT_BACKUP => 'Kit Backup',
            self::OPERATING => 'Operando',
            self::EXTERNAL_REPAIR => 'Em reparo externo',
            self::DISCARDED => 'Descartado',
        };
    }
}

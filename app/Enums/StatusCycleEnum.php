<?php

declare(strict_types=1);

namespace App\Enums;

enum StatusCycleEnum: string
{
    case NEW = 'new';

    case IN_PROGRESS = 'in_progress';

    case FINISHED = 'finished';

    public function label(): string
    {
        return match ($this) {
            self::NEW => 'Novo',
            self::IN_PROGRESS => 'Em execução',
            self::FINISHED => 'Finalizado',
        };
    }
}

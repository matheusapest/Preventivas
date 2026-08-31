<?php

namespace App\Enums;

enum PreventiveProfileRuleType: string
{
    case ALL = 'all';
    case SPECIFIC = 'specific';

    /**
     * Retorna o nome amigável da regra.
     */
    public function label(): string
    {
        return match ($this) {
            self::ALL => 'Todos',
            self::SPECIFIC => 'Específica',
        };
    }
}

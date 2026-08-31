<?php

namespace App\Enums;

enum ActivityKind: string
{
    case TEXT = 'text';
    case PHOTO = 'photo';
    case OPERATIONAL_COMPOSITION = 'operational_composition';
    case NUMBER = 'number';
    case BOOLEAN = 'boolean';

    /**
     * Retorna o nome amigável.
     */
    public function label(): string
    {
        return match ($this) {
            self::TEXT => 'Texto',
            self::PHOTO => 'Foto',
            self::OPERATIONAL_COMPOSITION => 'Composição Operacional',
            self::NUMBER => 'Número',
            self::BOOLEAN => 'Sim / Não',
        };
    }
}

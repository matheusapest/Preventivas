<?php

namespace App\Enums;

enum ActivityCategory: string
{
    case HARDWARE = 'hardware';

    case OPERATION = 'operation';

    case CLEANING = 'cleaning';

    case MAINTENANCE = 'maintenance';

    case ORGANIZATION = 'organization';

    /**
     * Retorna o nome amigável da categoria.
     */
    public function label(): string
    {
        return match ($this) {
            self::HARDWARE => 'Hardware',
            self::OPERATION => 'Operação',
            self::CLEANING => 'Limpeza',
            self::MAINTENANCE => 'Manutenção',
            self::ORGANIZATION => 'Organização',
        };
    }
}

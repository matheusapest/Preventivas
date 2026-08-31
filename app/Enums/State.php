<?php

namespace App\Enums;

enum State: string
{
    case RS = 'RS';
    case SC = 'SC';
    case SP = 'SP';

    /**
     * Retorna o nome amigável.
     */
    public function label(): string
    {
        return match ($this) {

            self::RS => 'Rio Grande do Sul',

            self::SC => 'Santa Catarina',

            self::SP => 'São Paulo',

        };
    }
}

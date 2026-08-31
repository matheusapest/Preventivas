<?php

namespace App\Enums;

enum BranchType: string
{
    case STORE = 'store';

    case WAREHOUSE = 'warehouse';

    case DISTRIBUTION_CENTER = 'distribution_center';

    /**
     * Retorna o nome amigável.
     */
    public function label(): string
    {
        return match ($this) {

            self::STORE => 'Loja',

            self::WAREHOUSE => 'Depósito',

            self::DISTRIBUTION_CENTER => 'Centro de Distribuição',

        };
    }
}

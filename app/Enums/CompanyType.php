<?php

namespace App\Enums;

enum CompanyType: string
{
    case GROUP = 'grupo';
    case OUTSOURCED = 'terceirizada';

    public function label(): string
    {
        return match ($this) {
            self::GROUP => 'Grupo Empresarial',
            self::OUTSOURCED => 'Empresa Terceirizada',
        };
    }
}

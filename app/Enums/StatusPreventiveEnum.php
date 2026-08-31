<?php

declare(strict_types=1);

namespace App\Enums;

enum StatusPreventiveEnum: string
{
    case NEW = 'new';
    case IN_PROGRESS = 'in_progress';
    case PENDING_APPROVAL = 'pending_approval';
    case APPROVED = 'approved';

    public function label(): string
    {
        return match ($this) {
            self::NEW => 'Nova',
            self::IN_PROGRESS => 'Em andamento',
            self::PENDING_APPROVAL => 'Aguardando aprovação',
            self::APPROVED => 'Aprovada',
        };
    }

    public function colorClass(): string
    {
        return match ($this) {
            self::NEW =>
                'bg-blue-100 text-blue-700',

            self::IN_PROGRESS =>
                'bg-orange-100 text-orange-700',

            self::PENDING_APPROVAL =>
                'bg-amber-100 text-amber-700',

            self::APPROVED =>
                'bg-emerald-100 text-emerald-700',
        };
    }

    /**
     * Indica se a preventiva ainda pode ser executada pelo técnico.
     */
    public function isExecutable(): bool
    {
        return match ($this) {
            self::NEW,
            self::IN_PROGRESS => true,

            self::PENDING_APPROVAL,
            self::APPROVED => false,
        };
    }

    /**
     * Indica se a preventiva deve ser apenas visualizada.
     */
    public function isViewOnly(): bool
    {
        return ! $this->isExecutable();
    }
}

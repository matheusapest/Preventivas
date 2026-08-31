<?php

declare(strict_types=1);

namespace App\Enums;

enum CycleReviewStatusEnum: string
{
    case PENDING = 'pending';

    case APPROVED = 'approved';

    case REJECTED = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Aguardando revisão',
            self::APPROVED => 'Aprovado',
            self::REJECTED => 'Reprovado',
        };
    }
}

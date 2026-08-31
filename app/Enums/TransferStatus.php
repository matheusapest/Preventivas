<?php

namespace App\Enums;

enum TransferStatus: string
{
    case SENT = 'sent';

    case RECEIVED = 'received';
}

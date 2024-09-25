<?php

declare(strict_types=1);

namespace App\Transfer\Domain\Enum;

enum OperationTypeEnum: string
{
    case CREDIT = 'credit';
    case DEBIT = 'debit';
}
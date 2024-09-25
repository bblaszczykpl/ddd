<?php

declare(strict_types=1);

namespace App\Transfer\Domain\Enum;

enum CurrencyEnum: string
{
    case PLN = 'pln';
    case EUR = 'eur';
}
<?php

declare(strict_types=1);

namespace App\Transfer\Domain\Exception;

use App\Transfer\Domain\Enum\CurrencyEnum;
use Exception;

class CurrencyMismatchException extends Exception
{

    public static function forCurrency(CurrencyEnum $currency): self
    {
        return new self(sprintf('currency mismatch: %d', $currency->value));
    }
}
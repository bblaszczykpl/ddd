<?php

declare(strict_types=1);

namespace App\Transfer\Domain\Exception;

use Exception;

class InsufficientFundsException extends Exception
{

    public static function forAmount(int $amount): self
    {
        return new self(sprintf('Insufficient funds for transfer: %d', $amount));
    }
}
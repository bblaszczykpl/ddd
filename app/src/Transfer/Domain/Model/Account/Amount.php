<?php

declare(strict_types=1);

namespace App\Transfer\Domain\Model\Account;

use App\Transfer\Domain\Enum\CurrencyEnum;
use Webmozart\Assert\Assert;

class Amount
{

    public function __construct(
        private readonly int          $amount,
        private readonly CurrencyEnum $currency,
    ) {
        Assert::greaterThan($amount, 0, 'Amount must be greater than 0');
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getCurrency(): CurrencyEnum
    {
        return $this->currency;
    }

}
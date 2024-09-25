<?php

declare(strict_types=1);

namespace App\Transfer\Domain\Model\Account;

use Webmozart\Assert\Assert;

class TransactionFee
{
    public function __construct(
        private readonly string $name,
        private readonly float $percentage
    ) {
        Assert::notEmpty($name, 'Transaction fee name cannot be empty');
        Assert::greaterThan($percentage,0, 'Transaction fee percentage must be greater than 0');
    }

    public function getName(): string
    {
        return $this->name;
    }


    public function getPercentage(): float
    {
        return $this->percentage;
    }

}
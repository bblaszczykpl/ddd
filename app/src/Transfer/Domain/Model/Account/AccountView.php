<?php

declare(strict_types=1);

namespace App\Transfer\Domain\Model\Account;

use DateTimeImmutable;

class AccountView
{
    public function __construct(
        private string $uuid,
        private int $balance,
        private string $currency,
        private DateTimeImmutable $balanceUpdateAt
    ) {}

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getBalance(): int
    {
        return $this->balance;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getBalanceUpdateAt(): DateTimeImmutable
    {
        return $this->balanceUpdateAt;
    }

}
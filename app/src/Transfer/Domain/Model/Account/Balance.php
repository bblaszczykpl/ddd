<?php

declare(strict_types=1);

namespace App\Transfer\Domain\Model\Account;

use DateTimeImmutable;

class Balance
{

    private DateTimeImmutable $date;

    public function __construct(
        private readonly int $amount,
    ) {
        $this->date = new DateTimeImmutable();
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }


}
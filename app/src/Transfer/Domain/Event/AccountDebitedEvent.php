<?php

declare(strict_types=1);

namespace App\Transfer\Domain\Event;

use App\Transfer\Domain\Model\Account\AccountUuid;
use App\Transfer\Domain\Model\Account\Amount;
use App\Transfer\Domain\Model\Account\Balance;
use DateTimeImmutable;

class AccountDebitedEvent implements DomainEventInterface
{
    private DateTimeImmutable $occurredOn;

    private string $uuid;

    public function __construct(
        private readonly AccountUuid $accountUuid,
        private readonly Amount $amount,
        private readonly Balance $balance
    ) {
        $this->occurredOn = new DateTimeImmutable();
        $this->uuid = (string) EventUuid::generate()->getUuid();
    }

    public function getOccurredOn(): DateTimeImmutable
    {
        return $this->occurredOn;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getAccountUuid(): string
    {
        return (string) $this->accountUuid;
    }

    public function getAmount(): int
    {
        return $this->amount->getAmount();
    }

    public function getBalance(): int
    {
        return $this->balance->getAmount();
    }

}
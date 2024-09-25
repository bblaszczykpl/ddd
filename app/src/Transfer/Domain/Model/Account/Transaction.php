<?php

declare(strict_types=1);

namespace App\Transfer\Domain\Model\Account;

use App\Transfer\Domain\Enum\OperationTypeEnum;
use DateTimeImmutable;

class Transaction
{
    private DateTimeImmutable $createdAt;

    public function __construct(
        private readonly Amount            $amount,
        private readonly OperationTypeEnum $operationType,
        private readonly ?TransactionFee   $fee,
        private Balance           $balance,
    ) {
        $calculatedBalance = $balance->getAmount();
        if (OperationTypeEnum::DEBIT === $this->operationType) {
            $calculatedBalance = $calculatedBalance - $amount->getAmount();
        } else {
            $calculatedBalance = $calculatedBalance + $amount->getAmount();
        }
        $this->balance = new Balance($calculatedBalance);
        $this->createdAt = new DateTimeImmutable();
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getAmount(): Amount
    {
        return $this->amount;
    }

    public function getOperationType(): OperationTypeEnum
    {
        return $this->operationType;
    }

    public function getFee(): ?TransactionFee
    {
        return $this->fee;
    }

    public function getBalance(): Balance
    {
        return $this->balance;
    }

}
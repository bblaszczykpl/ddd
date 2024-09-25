<?php

declare(strict_types=1);

namespace App\Transfer\Domain\Policy;

use App\Transfer\Domain\Enum\OperationTypeEnum;
use App\Transfer\Domain\Model\Account\Amount;
use App\Transfer\Domain\Model\Account\Transaction;
use App\Transfer\Domain\Model\Account\TransactionFee;

class DebitTransactionFeePolicy implements TransactionPolicyInterface
{
    private const FEE_NAME = 'Debit transaction fee';
    public  const SUPPORTED_OPERATION = OperationTypeEnum::DEBIT;

    private float $transactionFee = 0.005;

    public function apply(Transaction $transaction): Transaction
    {
        if (OperationTypeEnum::DEBIT !== $transaction->getOperationType()) {

            return $transaction;
        }

        $amount = $this->calculateAmount($transaction->getAmount());

        return new Transaction(
            $amount,
            $transaction->getOperationType(),
            new TransactionFee(self::FEE_NAME, $this->transactionFee),
            $transaction->getBalance()
        );
    }

    private function calculateAmount(Amount $amount): Amount
    {
        $calculated = $amount->getAmount() + ($amount->getAmount() * $this->transactionFee);

        return new Amount(
            (int) $calculated,
            $amount->getCurrency()
        );
    }

    public function supports(OperationTypeEnum $type): bool
    {
        return self::SUPPORTED_OPERATION === $type;
    }
}
<?php

declare(strict_types=1);

namespace App\Transfer\Domain\Policy;

use App\Transfer\Domain\Model\Account\Transaction;

class DomainTransactionPolicyProvider
{
    private array $policies = [
        DebitTransactionFeePolicy::class
    ];

    public function apply(Transaction $transaction): Transaction
    {
        foreach($this->policies as $policy) {
            /** @var TransactionPolicyInterface $instance */
            $instance = new $policy;
            if (false === $instance->supports($transaction->getOperationType())) {
                continue;
            }

            $transaction = $instance->apply($transaction);
        }

        return $transaction;
    }
}
<?php

declare(strict_types=1);

namespace App\Transfer\Domain\Specification;

use App\Transfer\Domain\Model\Account\Transaction;

class AllowedTransactionSpecification
{
    public function isSatisfied(Transaction $transaction): bool
    {
        if (0 <= $transaction->getBalance()->getAmount()) {
            return true;
        }

        return false;
    }
}
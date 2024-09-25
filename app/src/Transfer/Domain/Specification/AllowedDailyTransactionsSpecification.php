<?php

declare(strict_types=1);

namespace App\Transfer\Domain\Specification;

use App\Transfer\Domain\Model\Account\Transaction;
use App\Transfer\Domain\Model\Account\TransactionCollection;
use DateTimeImmutable;

class AllowedDailyTransactionsSpecification
{
    private int $dailyTransactionLimit = 3;

    public function isSatisfied(TransactionCollection $transactions, DateTimeImmutable $date): bool
    {
        $counter = 0;
        $iterator = $transactions->getIterator();

        while (true === $iterator->valid()) {
            $transaction = $iterator->current();
            $interval = $transaction->getCreatedAt()->diff($date);
            if ($interval->days === 0) {
                $counter++;
            }
            $iterator->next();
        }

        if ($counter >= $this->dailyTransactionLimit) {
            return false;
        }

        return true;
    }
}
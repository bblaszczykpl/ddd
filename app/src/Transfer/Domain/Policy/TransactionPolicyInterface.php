<?php

declare(strict_types=1);

namespace App\Transfer\Domain\Policy;

use App\Transfer\Domain\Enum\OperationTypeEnum;
use App\Transfer\Domain\Model\Account\Transaction;

interface TransactionPolicyInterface
{
    public function apply(Transaction $transaction): Transaction;

    public function supports(OperationTypeEnum $type): bool;
}
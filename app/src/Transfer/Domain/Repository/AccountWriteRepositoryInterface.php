<?php

declare(strict_types=1);

namespace App\Transfer\Domain\Repository;

use App\Transfer\Domain\Model\Account\AccountAggregate;

interface AccountWriteRepositoryInterface
{
    public function persist(AccountAggregate $account): void;
}
<?php

namespace Tests\Mother;

use App\Transfer\Domain\Enum\CurrencyEnum;
use App\Transfer\Domain\Model\Account\AccountAggregate;
use App\Transfer\Domain\Model\Account\AccountUuid;
use App\Transfer\Domain\Model\Account\Balance;
use App\Transfer\Domain\Model\Account\TransactionCollection;
use App\Transfer\Domain\Policy\DomainTransactionPolicyProvider;

class AccountAggregateMother
{
    public const CURRENCY = CurrencyEnum::EUR;
    public const BALANCE = 10000;

    public static function new(
        ?CurrencyEnum $currency = null,
        ?int $balance = null,
    ): AccountAggregate {
        return AccountAggregate::create(
            AccountUuid::generate(),
            $currency ?? self::CURRENCY,
            new Balance($balance ?? self::BALANCE),
            new TransactionCollection(),
            new DomainTransactionPolicyProvider()
        );
    }
}
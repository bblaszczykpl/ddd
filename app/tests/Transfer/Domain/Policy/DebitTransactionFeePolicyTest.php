<?php

declare(strict_types=1);

namespace Tests\Transfer\Domain\Policy;

use App\Transfer\Domain\Enum\CurrencyEnum;
use App\Transfer\Domain\Enum\OperationTypeEnum;
use App\Transfer\Domain\Model\Account\Amount;
use App\Transfer\Domain\Model\Account\Balance;
use App\Transfer\Domain\Model\Account\Transaction;
use App\Transfer\Domain\Policy\DebitTransactionFeePolicy;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class DebitTransactionFeePolicyTest extends TestCase
{

    #[DataProvider("dataProvider")]
    public function testItAppliesPolicy($operationType, $amountValue, $balanceAmount, $transactionFee,
                                        $expectedAmountValue, $expectedBalanceValue): void
    {
        $balance = $this->createMock(Balance::class);
        $balance->method('getAmount')->willReturn($balanceAmount);
        $amount = $this->createMock(Amount::class);
        $amount->method('getAmount')->willReturn($amountValue);
        $amount->method('getCurrency')->willReturn(CurrencyEnum::EUR);

        $expectedBalance = $this->createMock(Balance::class);
        $expectedBalance->method('getAmount')->willReturn($expectedBalanceValue);
        $expectedAmount = $this->createMock(Amount::class);
        $expectedAmount->method('getAmount')->willReturn($expectedAmountValue);
        $expectedAmount->method('getCurrency')->willReturn(CurrencyEnum::EUR);

        $transaction = $this->createMock(Transaction::class);
        $transaction->method('getOperationType')->willReturn($operationType);
        $transaction->method('getAmount')->willReturn($amount);
        $transaction->method('getBalance')->willReturn($balance);
        $transaction->method('getFee')->willReturn($transactionFee);

        $policy = new DebitTransactionFeePolicy();
        $modifiedTransaction = $policy->apply($transaction);

        $this->assertEquals($expectedAmountValue, $modifiedTransaction->getAmount()->getAmount());
        $this->assertEquals($expectedBalanceValue, $modifiedTransaction->getBalance()->getAmount());
    }

    public static function dataProvider(): array
    {
        return [
            [
                OperationTypeEnum::CREDIT,
                1000,
                1000,
                null,
                1000,
                1000
            ],
            [
                OperationTypeEnum::DEBIT,
                1000,
                10000,
                null,
                1005,
                8995
            ],
        ];
    }
}
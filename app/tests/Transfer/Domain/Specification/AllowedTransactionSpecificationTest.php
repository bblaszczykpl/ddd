<?php

declare(strict_types=1);

namespace Tests\Transfer\Domain\Specification;

use App\Transfer\Domain\Model\Account\Balance;
use App\Transfer\Domain\Model\Account\Transaction;
use App\Transfer\Domain\Specification\AllowedTransactionSpecification;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class AllowedTransactionSpecificationTest extends TestCase
{
    #[DataProvider("dataProvider")]
    public function testAllowsPositiveBalance($amount, $satisfied): void
    {
        $transaction = $this->createMock(Transaction::class);
        $balance = $this->createMock(Balance::class);
        $balance->method('getAmount')->willReturn($amount);
        $transaction->method('getBalance')->willReturn($balance);

        $specification = new AllowedTransactionSpecification();
        $this->assertEquals($satisfied, $specification->isSatisfied($transaction));
    }

    public static function dataProvider(): array
    {
        return [
            [
                'amount' => -1,
                'satisfied' => false
            ],
            [
                'amount' => 0,
                'satisfied' => true
            ],
            [
                'amount' => 123,
                'satisfied' => true
            ]
        ];
    }
}
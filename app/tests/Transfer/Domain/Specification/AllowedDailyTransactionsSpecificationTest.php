<?php

declare(strict_types=1);

namespace Tests\Transfer\Domain\Specification;

use App\Transfer\Domain\Model\Account\Transaction;
use App\Transfer\Domain\Model\Account\TransactionCollection;
use App\Transfer\Domain\Specification\AllowedDailyTransactionsSpecification;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class AllowedDailyTransactionsSpecificationTest extends TestCase
{
    #[DataProvider("dataProvider")]
    public function testAllowsDailyTransactions($date, $satisfied, $transactionDates): void
    {
        $collection = new TransactionCollection();
        foreach ($transactionDates as $transactionDate) {
            $transaction = $this->createMock(Transaction::class);
            $transaction->method('getCreatedAt')->willReturn($transactionDate);
            $collection->add($transaction);
        }
        $specification = new AllowedDailyTransactionsSpecification();
        $this->assertEquals($satisfied, $specification->isSatisfied($collection, $date));
    }

    public static function dataProvider(): array
    {
        return [
            [
                new DateTimeImmutable("2024-01-06"),
                true,
                [
                    new DateTimeImmutable("2024-01-01"),
                    new DateTimeImmutable("2024-01-02"),
                    new DateTimeImmutable("2024-01-03"),
                    new DateTimeImmutable("2024-01-04"),
                    new DateTimeImmutable("2024-01-05"),
                    new DateTimeImmutable("2024-01-06"),
                ]
            ],
            [
                new DateTimeImmutable("2024-01-06"),
                true,
                [
                    new DateTimeImmutable("2024-01-01"),
                    new DateTimeImmutable("2024-01-01"),
                    new DateTimeImmutable("2024-01-01"),
                    new DateTimeImmutable("2024-01-04"),
                    new DateTimeImmutable("2024-01-05"),
                    new DateTimeImmutable("2024-01-06"),
                ]
            ],
            [
                new DateTimeImmutable("2024-01-06"),
                false,
                [
                    new DateTimeImmutable("2024-01-01"),
                    new DateTimeImmutable("2024-01-01"),
                    new DateTimeImmutable("2024-01-01"),
                    new DateTimeImmutable("2024-01-06"),
                    new DateTimeImmutable("2024-01-06"),
                    new DateTimeImmutable("2024-01-06"),
                ]
            ],
        ];
    }
}

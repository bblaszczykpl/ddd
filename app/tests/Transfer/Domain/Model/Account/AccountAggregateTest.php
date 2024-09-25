<?php

declare(strict_types=1);

namespace Tests\Transfer\Domain\Model\Account;

use App\Transfer\Domain\Enum\CurrencyEnum;
use App\Transfer\Domain\Exception\AllowedDailyTransactionsLimitException;
use App\Transfer\Domain\Exception\CurrencyMismatchException;
use App\Transfer\Domain\Exception\InsufficientFundsException;
use App\Transfer\Domain\Model\Account\Amount;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\Mother\AccountAggregateMother;
use PHPUnit\Framework\TestCase;
use Webmozart\Assert\InvalidArgumentException;

class AccountAggregateTest extends TestCase
{

    #[DataProvider("currencyProvider")]
    public function testItThrowsExceptionOnCurrencyMismatch($accountCurrency, $operationCurrency, $operation): void
    {
        $account = AccountAggregateMother::new(currency: $accountCurrency);
        $amount = new Amount(1000, $operationCurrency);
        $this->expectException(CurrencyMismatchException::class);
        if ('credit' === $operation) {
            $account->credit($amount);
        } else {
            $account->debit($amount);
        }
    }

    public function testItThrowsExceptionOnZeroPayment(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Amount must be greater than 0');
        $account = AccountAggregateMother::new();
        $amount = new Amount(0, CurrencyEnum::EUR);
        $account->credit($amount);
    }

    public function testItThrowsExceptionOnNegativePayment(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Amount must be greater than 0');
        $account = AccountAggregateMother::new();
        $amount = new Amount(-12000, CurrencyEnum::EUR);
        $account->credit($amount);
    }


    #[DataProvider("amountProvider")]
    public function testItIncreasesBalanceOnCredit($initialBalance, $operations, $expectedBalance): void
    {
        $account = AccountAggregateMother::new(currency: CurrencyEnum::PLN, balance: $initialBalance);
        foreach($operations as $operation) {
            $amount = new Amount($operation, CurrencyEnum::PLN);
            $account->credit($amount);
        }
        $view = $account->asView();
        $this->assertEquals($expectedBalance, $view->getBalance());
    }

    #[DataProvider("amountDebitProvider")]
    public function testItDecreasesBalanceOnCredit($initialBalance, $operations, $expectedBalance): void
    {
        $account = AccountAggregateMother::new(currency: CurrencyEnum::PLN, balance: $initialBalance);
        foreach($operations as $operation) {
            $amount = new Amount($operation, CurrencyEnum::PLN);
            $account->debit($amount);
        }
        $view = $account->asView();
        $this->assertEquals($expectedBalance, $view->getBalance());
    }

    public function testItThrowsExceptionOnOverdraft(): void
    {
        $account = AccountAggregateMother::new(currency: CurrencyEnum::EUR, balance: 10000);
        $amount = new Amount(amount: 12000, currency: CurrencyEnum::EUR);
        $this->expectException(InsufficientFundsException::class);
        $account->debit($amount);
    }

    public function testItThrowsExceptionOnDailyTransactionsLimitExceeded(): void
    {
        $account = AccountAggregateMother::new(currency: CurrencyEnum::EUR, balance: 10000);
        $amount = new Amount(amount: 120, currency: CurrencyEnum::EUR);
        $this->expectException(AllowedDailyTransactionsLimitException::class);
        $account->debit($amount);
        $account->debit($amount);
        $account->debit($amount);
        $account->debit($amount);
    }

    public static function amountProvider(): array
    {
        return [
            [
                10000,
                [
                    1200, 3456, 7654
                ],
                22310
            ],
            [
                140000,
                [
                    1231200, 333456, 457654
                ],
                2162310
            ]
        ];
    }

    public static function amountDebitProvider(): array
    {
        return [
            [
                223150,
                [
                    1200, 3456, 7654
                ],
                198469
            ],
            [
                21623159,
                [
                    1231200, 333456, 457654
                ],
                17568428,
            ]
        ];
    }

    public static function currencyProvider(): array
    {
        return [
            [
                CurrencyEnum::EUR,
                CurrencyEnum::PLN,
                'credit'
            ],
            [
            CurrencyEnum::EUR,
                CurrencyEnum::PLN,
                'debit'
            ]
        ];
    }
}
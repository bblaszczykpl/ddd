<?php

declare(strict_types=1);

namespace App\Transfer\Domain\Model\Account;

use App\Transfer\Domain\Enum\CurrencyEnum;
use App\Transfer\Domain\Enum\OperationTypeEnum;
use App\Transfer\Domain\Event\AccountCreditedEvent;
use App\Transfer\Domain\Event\AccountDebitedEvent;
use App\Transfer\Domain\Exception\AllowedDailyTransactionsLimitException;
use App\Transfer\Domain\Exception\CurrencyMismatchException;
use App\Transfer\Domain\Exception\InsufficientFundsException;
use App\Transfer\Domain\Model\AggregateRootAbstract;
use App\Transfer\Domain\Policy\DomainTransactionPolicyProvider;
use App\Transfer\Domain\Specification\AllowedDailyTransactionsSpecification;
use App\Transfer\Domain\Specification\AllowedTransactionSpecification;
use DateTimeImmutable;

class AccountAggregate extends AggregateRootAbstract
{
    private function __construct(
        private AccountUuid $uuid,
        private CurrencyEnum $currency,
        private Balance $balance,
        private TransactionCollection $transactions,
        private DomainTransactionPolicyProvider $policyProvider
    ) {
    }

    public static function create(
        AccountUuid $uuid,
        CurrencyEnum $currency,
        Balance $balance,
        TransactionCollection $transactions,
        DomainTransactionPolicyProvider $policyProvider
    ): self
    {
        return new self(
            $uuid,
            $currency,
            $balance,
            $transactions,
            $policyProvider
        );
    }

    public static function restore(
        AccountUuid $uuid,
        CurrencyEnum $currency,
        Balance $balance,
        TransactionCollection $transactions,
        DomainTransactionPolicyProvider $policyProvider
    ): self
    {
        return new self(
            $uuid,
            $currency,
            $balance,
            $transactions,
            $policyProvider
        );
    }

    /**
     * @throws CurrencyMismatchException
     */
    public function credit(Amount $amount): void
    {
        $this->validateCurrency($amount);
        $transaction = new Transaction(
            $amount,
            OperationTypeEnum::CREDIT,
            null,
            $this->balance,
        );
        $this->transactions->add($transaction);
        $this->balance = $transaction->getBalance();

        $this->raise(new AccountCreditedEvent($this->uuid, $amount, $this->balance));
    }

    /**
     * @throws InsufficientFundsException
     * @throws CurrencyMismatchException
     * @throws AllowedDailyTransactionsLimitException
     */
    public function debit(Amount $amount): void
    {
        $this->validateCurrency($amount);
        $transaction = new Transaction(
            $amount,
            OperationTypeEnum::DEBIT,
            null,
            $this->balance,
        );
        $transaction = $this->policyProvider->apply($transaction);
        $this->validateDebitOperation($transaction);

        $this->transactions->add($transaction);
        $this->balance = $transaction->getBalance();

        $this->raise(new AccountDebitedEvent($this->uuid, $amount, $this->balance));
    }

    public function asView(): AccountView
    {
        return new AccountView(
            (string) $this->uuid,
            $this->balance->getAmount(),
            $this->currency->value,
            $this->balance->getDate()
        );
    }

    /**
     * @throws CurrencyMismatchException
     */
    private function validateCurrency(Amount $amount): void
    {
        if ($this->currency !== $amount->getCurrency()) {
            throw CurrencyMismatchException::forCurrency($amount->getCurrency());
        }
    }

    /**
     * @throws InsufficientFundsException
     * @throws AllowedDailyTransactionsLimitException
     */
    private function validateDebitOperation(Transaction $transaction): void
    {
        $specification = new AllowedTransactionSpecification();
        if (false === $specification->isSatisfied($transaction)) {
            throw InsufficientFundsException::forAmount($transaction->getAmount()->getAmount());
        }

        $specification = new AllowedDailyTransactionsSpecification();
        if (false === $specification->isSatisfied($this->transactions, new DateTimeImmutable())) {
            throw new AllowedDailyTransactionsLimitException();
        }
    }
}
<?php

declare(strict_types=1);

namespace App\Transfer\Domain\Model\Account;

use ArrayIterator;
use IteratorAggregate;

class TransactionCollection implements IteratorAggregate
{
    protected array $items = [];

    public function __construct(Transaction ...$transactions)
    {
        foreach ($transactions as $transaction) {
            $this->add($transaction);
        }
    }

    public function add(Transaction $transaction): void
    {
        $this->items[] = $transaction;
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function isEmpty(): bool
    {
        return 0 === $this->count();
    }

    public function first(): mixed
    {
        return reset($this->items);
    }

    public function last(): mixed
    {
        return end($this->items);
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->items);
    }
}

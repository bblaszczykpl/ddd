<?php

declare(strict_types=1);

namespace App\Transfer\Domain\Model;


use App\Transfer\Domain\Event\DomainEventInterface;

abstract class AggregateRootAbstract
{
    /** @var DomainEventInterface[]  */
    private array $events;

    /**
     * @return DomainEventInterface[]
     */
    public function getRaisedEvents(): array
    {
        return $this->events;
    }

    public function clearRaisedEvents(): void
    {
        $this->events = [];
    }

    protected function raise(DomainEventInterface $event): void
    {
        $this->events[] = $event;
    }
}

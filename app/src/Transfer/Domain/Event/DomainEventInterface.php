<?php

declare(strict_types=1);

namespace App\Transfer\Domain\Event;

use DateTimeImmutable;

interface DomainEventInterface
{
    public function getOccurredOn(): DateTimeImmutable;

    public function getUuid(): string;
}

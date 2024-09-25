<?php

declare(strict_types=1);

namespace App\Transfer\Domain\Model;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

trait UuidModelTrait
{
    public function __construct(
        private readonly UuidInterface $uuid,
    ) {
    }

    public function __toString(): string
    {
        return $this->getUuid()->toString();
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public static function generate(): self
    {
        return new self(
            Uuid::uuid4(),
        );
    }

}

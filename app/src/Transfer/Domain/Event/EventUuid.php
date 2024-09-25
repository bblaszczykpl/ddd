<?php

namespace App\Transfer\Domain\Event;

use App\Transfer\Domain\Model\UuidModelTrait;
use Stringable;

class EventUuid implements Stringable
{
    use UuidModelTrait;

}
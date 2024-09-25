<?php

declare(strict_types=1);

namespace App\Transfer\Domain\Model\Account;


use App\Transfer\Domain\Model\UuidModelTrait;
use Stringable;

class AccountUuid implements Stringable
{
    use UuidModelTrait;
}
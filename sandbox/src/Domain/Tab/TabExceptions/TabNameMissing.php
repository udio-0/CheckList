<?php

declare(strict_types=1);

namespace App\Domain\Tab\TabExceptions;

use App\Domain\DomainException\DomainRecordNotFoundException;

class TabNameMissing extends DomainRecordNotFoundException
{
    public $message = 'Tab cannot be created if "Name" is missing or empty.';

}
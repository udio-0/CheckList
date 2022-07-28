<?php

declare(strict_types=1);

namespace App\Domain\Tab\TabExceptions;

use App\Domain\DomainException\DomainRecordNotFoundException;

class TabNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'The tab you requested does not exist.';

}
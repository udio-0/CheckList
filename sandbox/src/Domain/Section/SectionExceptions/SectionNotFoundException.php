<?php

declare(strict_types=1);

namespace App\Domain\Section\SectionExceptions;

use App\Domain\DomainException\DomainRecordNotFoundException;

class SectionNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'The Section you requested does not exist.';

}
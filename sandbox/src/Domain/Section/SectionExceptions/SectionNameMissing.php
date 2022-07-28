<?php

declare(strict_types=1);

namespace App\Domain\Section\SectionExceptions;

use App\Domain\DomainException\DomainRecordNotFoundException;

class SectionNameMissing extends DomainRecordNotFoundException
{
    public $message = 'Section cannot be created if "Name" is missing or empty.';

}
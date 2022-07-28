<?php

declare(strict_types=1);

namespace App\Domain\Section\SectionExceptions;

use App\Domain\DomainException\DomainException;

class SectionNameLengthExceeded extends DomainException
{
    public $message = "The length of the Name exceeded 100 characters.";
}
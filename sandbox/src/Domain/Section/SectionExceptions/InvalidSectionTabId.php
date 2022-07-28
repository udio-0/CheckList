<?php

declare(strict_types=1);

namespace App\Domain\Section\SectionExceptions;

use App\Domain\DomainException\DomainException;

class InvalidSectionTabId extends DomainException
{
    public $message = "The Tab id given is not valid or does not exist.";
}
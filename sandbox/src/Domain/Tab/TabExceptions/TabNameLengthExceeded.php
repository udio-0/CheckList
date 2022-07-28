<?php

declare(strict_types=1);

namespace App\Domain\Tab\TabExceptions;

use App\Domain\DomainException\DomainException;

class TabNameLengthExceeded extends DomainException
{
    public $message = "The length of the Name exceeded 100 characters.";
}
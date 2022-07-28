<?php

declare(strict_types=1);

namespace App\Domain\Tab\TabExceptions;

use App\Domain\DomainException\DomainException;

class InvalidTabTickedId extends DomainException
{
    public $message = "The ticked id given is not valid or does not exist.";
}
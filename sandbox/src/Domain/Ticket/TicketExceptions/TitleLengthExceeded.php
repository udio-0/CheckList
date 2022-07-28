<?php

declare(strict_types=1);

namespace App\Domain\Ticket\TicketExceptions;

use App\Domain\DomainException\DomainException;

class TitleLengthExceeded extends DomainException
{
    public $message = "The length of the Title exceeded 100 characters.";
}
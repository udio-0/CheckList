<?php

declare(strict_types=1);

namespace App\Domain\Ticket\TicketExceptions;

use App\Domain\DomainException\DomainException;

class TicketTitleMissing extends DomainException
{

    public $message = "Ticket cannot be created if 'Title' is missing or empty.";
}
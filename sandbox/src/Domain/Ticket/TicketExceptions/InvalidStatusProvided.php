<?php

declare(strict_types=1);

namespace App\Domain\Ticket\TicketExceptions;

use App\Domain\DomainException\DomainException;

class InvalidStatusProvided extends DomainException
{

    public $message = "The status provided is not valid. Valid";

}
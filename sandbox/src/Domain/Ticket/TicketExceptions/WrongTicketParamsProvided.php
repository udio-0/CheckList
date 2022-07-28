<?php

declare(strict_types=1);

namespace App\Domain\Ticket\TicketExceptions;

use App\Domain\DomainException\DomainException;

class WrongTicketParamsProvided extends DomainException
{
    public $message = "One or more parameters provided do not match the database columns";
}
<?php

declare(strict_types=1);

namespace App\Domain\Ticket\TicketExceptions;

use App\Domain\DomainException\DomainRecordNotFoundException;

class TicketNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'The ticket you requested does not exist.';
}

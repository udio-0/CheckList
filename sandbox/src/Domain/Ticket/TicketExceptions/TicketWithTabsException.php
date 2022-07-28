<?php

declare(strict_types=1);

namespace App\Domain\Ticket\TicketExceptions;

use App\Domain\DomainException\DomainException;

class TicketWithTabsException extends DomainException
{
    public $message = 'This ticked still has tabs, please delete all tabs before deleting a ticket.';
}

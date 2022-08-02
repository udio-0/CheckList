<?php

declare(strict_types=1);

namespace App\Domain\Ticket\TicketExceptions;

use App\Domain\DomainException\DomainException;

class DescriptionLengthExceeded extends DomainException
{

    public $message = "The Description provided is not valid. Valid";

}
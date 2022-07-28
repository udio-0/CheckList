<?php

namespace App\Domain\Ticket;

use phpDocumentor\Reflection\Types\This;
use ReflectionClass;

class TicketStatus
{
     const OPEN = 'open';
     const IN_PROGRESS = 'in progress';
     const CANCELED = 'canceled';
     const CLOSED = 'closed';

    public static function allPossibleStatuses(): array
    {

        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();

    }
}
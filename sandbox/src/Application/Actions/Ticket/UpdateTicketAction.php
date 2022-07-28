<?php

declare(strict_types=1);

namespace App\Application\Actions\Ticket;

use App\Domain\Ticket\TicketExceptions\InvalidStatusProvided;
use App\Domain\Ticket\TicketExceptions\TicketNotFoundException;
use App\Domain\Ticket\TicketExceptions\TitleLengthExceeded;
use Psr\Http\Message\ResponseInterface as Response;

class UpdateTicketAction extends TicketAction
{
    /**
    * @throws TitleLengthExceeded
    * @throws InvalidStatusProvided
    * {@inheritdoc}
    */
    protected function action(): Response
    {
        $ticketId = (int) $this->resolveArg('id');

        $ticket = $this->ticketRepository->findTicketOfId($ticketId);

        $ticket->updateInMemoryTicket($this->getFormData());

        $this->ticketRepository->updateTicket($ticket);

        $updatedTicket = $this->ticketRepository->findTicketOfId($ticketId);

        $this->logger->info("Ticket of id `${ticketId}` was updated.");

        return $this->respondWithData($updatedTicket);
    }
}

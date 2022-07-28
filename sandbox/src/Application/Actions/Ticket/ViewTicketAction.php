<?php

declare(strict_types=1);

namespace App\Application\Actions\Ticket;

use Psr\Http\Message\ResponseInterface as Response;

class ViewTicketAction extends TicketAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $ticketId = (int) $this->resolveArg('id');

        $ticket = $this->ticketRepository->findTicketOfId($ticketId);

        $id = $ticket->getId();

        $this->logger->info("Ticket of id `${id}` was viewed.");

        return $this->respondWithData($ticket);
    }
}

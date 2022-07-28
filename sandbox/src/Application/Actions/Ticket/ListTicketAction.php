<?php

declare(strict_types=1);

namespace App\Application\Actions\Ticket;

use Psr\Http\Message\ResponseInterface as Response;

class ListTicketAction extends TicketAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $this->logger->info("Tickets list was viewed.");

        $tickets = $this->ticketRepository->findAll();

        return $this->respondWithData($tickets);
    }
}

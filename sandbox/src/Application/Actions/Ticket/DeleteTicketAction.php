<?php

declare(strict_types=1);

namespace App\Application\Actions\Ticket;

use App\Domain\Ticket\TicketExceptions\TicketWithTabsException;
use PDOException;
use Psr\Http\Message\ResponseInterface as Response;

class DeleteTicketAction extends TicketAction
{
    /**
     * {@inheritdoc}
     * @throws TicketWithTabsException
     */
    protected function action(): Response
    {
        $ticketId = (int) $this->resolveArg('id');

        $ticket = $this->ticketRepository->findTicketOfId($ticketId);

        try {
            $ticketDeleted = $this->ticketRepository->deleteTicketOfId($ticket->getId());
        } catch (PDOException $e){
            throw new TicketWithTabsException();
        }

        $this->logger->info("Ticket of id `${ticketId}` was deleted.");

        return $this->respondWithData($ticketDeleted ? "Ticket was deleted." : "Something went wrong.");
    }
}

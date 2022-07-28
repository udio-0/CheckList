<?php

declare(strict_types=1);

namespace App\Application\Actions\Ticket;

use App\Domain\Ticket\Ticket;
use App\Domain\Ticket\TicketExceptions\TicketTitleMissing;
use App\Domain\Ticket\TicketExceptions\TitleLengthExceeded;
use Psr\Http\Message\ResponseInterface as Response;

class CreateTicketAction extends TicketAction
{
    /**
     * @throws TicketTitleMissing
     * @throws TitleLengthExceeded
     */
    protected function action(): Response
    {

        $ticket = Ticket::createTicketFromArray($this->getFormData());

        $isTicketCreated = $this->ticketRepository->createNewTicket($ticket);

        $this->logger->info("New Ticket added.");

        return $this->respondWithData($isTicketCreated ? "New Ticket was added." : "Something went wrong");
    }



}
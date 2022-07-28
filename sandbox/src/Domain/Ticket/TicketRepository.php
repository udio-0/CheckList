<?php

declare(strict_types=1);

namespace App\Domain\Ticket;

interface TicketRepository
{
    /**
     * @return Ticket[]
     */
    public function findAll(): array;

    /**
     * @param int $id
     * @return Ticket
     */
    public function findTicketOfId(int $id): ? Ticket;

    public function createNewTicket(Ticket $ticket) : bool;

    public function updateTicket(Ticket $ticket) : bool;

    public function deleteTicketOfId(int $id): bool;
}

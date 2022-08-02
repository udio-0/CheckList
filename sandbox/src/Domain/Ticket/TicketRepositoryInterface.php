<?php

declare(strict_types=1);

namespace App\Domain\Ticket;

interface TicketRepositoryInterface
{
    /**
     * @return Ticket[]
     */
    public function findAll(): array;

    /**
     * @param int $id
     * @return Ticket
     */
    public function findTicketById(int $id): ? Ticket;

    public function createNewTicket(Ticket $ticket) : bool;

    public function updateTicket(Ticket $ticket) : bool;

    public function deleteTicketById(int $id): bool;
}

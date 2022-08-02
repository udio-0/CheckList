<?php

declare(strict_types=1);

namespace App\Application\Actions\Ticket;

use Psr\Http\Message\ResponseInterface as Response;

class ListTicketAction extends TicketAction
{
    /**
     * @OA\Get(
     *     path="/api/tickets",
     *     summary="Get all tickets",
     *     operationId="ListTickets",
     *     @OA\Response(
     *     response=200,
     *     description="list tickets",
     *     @OA\JsonContent(
     *          type="array",
     *          @OA\Items(ref="#components/schemas/Ticket")
     * )
     * )
     * )
     *
     */
    protected function action(): Response
    {
        $this->logger->info("Tickets list was viewed.");

        $tickets = $this->ticketRepository->findAll();

        return $this->respondWithData($tickets);
    }
}

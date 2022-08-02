<?php

declare(strict_types=1);

namespace App\Application\Actions\Ticket;

use App\Domain\Ticket\TicketExceptions\TicketWithTabsException;
use PDOException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class DeleteTicketAction extends TicketAction
{
    /**
     * @OA\Delete(
     *     path="/api/tickets/{id}",
     *     summary="Deletes ticket by Id",
     *     operationId="deleteTicket",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Ticket id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64",
     *             minimum=1
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ticket deleted",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid ID supplied",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Ticket not found",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Ticket with tabs cannot be deleted. First Delete all its tabs"
     *     )
     * )
     *
     * @throws TicketWithTabsException
     * @throws HttpBadRequestException
     */
    protected function action(): Response
    {
        $ticketId = (int) $this->resolveArg('id');

        $ticket = $this->ticketRepository->findTicketById($ticketId);

        try {
            $ticketDeleted = $this->ticketRepository->deleteTicketById($ticket->getId());
        } catch (PDOException $e){
            throw new TicketWithTabsException();
        }

        $this->logger->info("Ticket of id `${ticketId}` was deleted.");

        return $this->respondWithData($ticketDeleted ? "Ticket was deleted." : "Something went wrong.");
    }
}

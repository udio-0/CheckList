<?php

declare(strict_types=1);

namespace App\Application\Actions\Ticket;

use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class ViewTicketAction extends TicketAction
{
    /**
     * @OA\Get(
     *     path="/api/tickets/{id}",
     *     summary="Get ticket by id",
     *     operationId="getTicketById",
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="Ticket id.",
     *          @OA\Schema(
     *             type="integer",
     *             format="int64",
     *             minimum=1
     *          )
     *      ),
     *     @OA\Response(
     *          response=200,
     *          description="Get a single ticket.",
     *          @OA\JsonContent(ref="#/components/schemas/Ticket")
     *      ),
     *     @OA\Response(
     *          response=404,
     *          description="Ticket not found."
     *      )
     * )
     *
     * @throws HttpBadRequestException
     */
    protected function action(): Response
    {
        $ticketId = (int) $this->resolveArg('id');

        $ticket = $this->ticketRepository->findTicketById($ticketId);

        $id = $ticket->getId();

        $this->logger->info("Ticket of id `${id}` was viewed.");

        return $this->respondWithData($ticket);
    }
}

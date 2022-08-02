<?php

declare(strict_types=1);

namespace App\Application\Actions\Ticket;

use App\Domain\Ticket\TicketExceptions\DescriptionLengthExceeded;
use App\Domain\Ticket\TicketExceptions\InvalidStatusProvided;
use App\Domain\Ticket\TicketExceptions\TitleLengthExceeded;
use App\Domain\Ticket\TicketValidations as Validate;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class UpdateTicketAction extends TicketAction
{
    /**
     * @OA\Put(
     *     path="/api/tickets/{id}",
     *     summary="Update a ticket",
     *     operationId="updateTicket",
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
     *      @OA\Response(response=200, description="Update successful"),
     *      @OA\Response(
     *         response=400,
     *         description="Invalid title"
     *     ),
     *     @OA\Response(
     *          response=404,
     *          description="Ticket not found."
     *      ),
     *     @OA\RequestBody(
     *         description="Ticket object",
     *         required=true,
     *         @OA\JsonContent(
     *              required={"title"},
     *              @OA\Property(property="title", type="string", format="text", example="Ticket"),
     *              @OA\Property(property="description", type="string", format="text", example="SomeText"),
     *              @OA\Property(property="status", type="string", format="text", example="open")
     *         )
     *     )
     * )
     * @throws TitleLengthExceeded
     * @throws InvalidStatusProvided
     * @throws HttpBadRequestException
     * @throws DescriptionLengthExceeded
     */
    protected function action(): Response
    {
        $ticketId = (int) $this->resolveArg('id');

        $ticket = $this->ticketRepository->findTicketById($ticketId);


        $data = $this->getFormData();

        $ticket->setTitle(Validate::title($data['title']));
        $ticket->setDescription(Validate::description($data['description']));
        $ticket->setStatus(Validate::status($data['status']));

        $this->ticketRepository->updateTicket($ticket);

        $this->logger->info("Ticket of id $ticketId was updated.");

        return $this->respondWithData($ticket);
    }
}

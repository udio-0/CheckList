<?php

declare(strict_types=1);

namespace App\Application\Actions\Ticket;

use App\Domain\Ticket\Ticket;
use App\Domain\Ticket\TicketExceptions\TicketTitleMissing;
use App\Domain\Ticket\TicketExceptions\TitleLengthExceeded;
use App\Domain\Ticket\TicketValidations;
use Psr\Http\Message\ResponseInterface as Response;

class CreateTicketAction extends TicketAction
{
    /**
     * @OA\Post(
     *     path="/api/tickets",
     *     summary="Create a new ticket",
     *     operationId="createTicket",
     *     @OA\Response(response=201, description="Creation successful"),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid title"
     *     ),
     *     @OA\RequestBody(
     *         description="Ticket object",
     *         required=true,
     *         @OA\JsonContent(
     *              required={"title"},
     *              @OA\Property(property="title", type="string", format="text", example="Ticket"),
     *              @OA\Property(property="description", type="string", format="text", example="SomeText")
     *         )
     *     )
     * )
     * @throws TicketTitleMissing
     * @throws TitleLengthExceeded
     */
    protected function action(): Response
    {
        $ticket = TicketValidations::createTicketFromArray($this->getFormData());

        $isTicketCreated = $this->ticketRepository->createNewTicket($ticket);

        $this->logger->info("New Ticket added.");

        return $this->respondWithData($isTicketCreated ? "New Ticket was added." : "Something went wrong");
    }
}
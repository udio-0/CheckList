<?php

declare(strict_types=1);

namespace App\Domain\Ticket;

use App\Domain\Ticket\TicketExceptions\DescriptionLengthExceeded;
use App\Domain\Ticket\TicketExceptions\InvalidStatusProvided;
use App\Domain\Ticket\TicketExceptions\TitleLengthExceeded;
use App\Domain\Ticket\TicketExceptions\TicketTitleMissing;

class TicketValidations
{
    private const MAX_TITLE_LENGTH = 100;

    private const MAX_DESCRIPTION_LENGTH = 255;

    public static function ticketFromArray(array $params): Ticket
    {
        return new Ticket(
            $params['id'],
            $params['title'],
            $params['description'],
            $params['status'],
            $params['date'],
            $params['last_update']);
    }
    /**
     * @throws TicketTitleMissing
     * @throws TitleLengthExceeded
     */
    public static function createTicketFromArray(array $params) : Ticket
    {
        if (!array_key_exists('title',$params)){
            throw new TicketTitleMissing();
        }
        if ($params['title'] == ""){
            throw new TicketTitleMissing();
        }
        if (strlen($params['title']) > self::MAX_TITLE_LENGTH){
            throw new TitleLengthExceeded();
        }

        return new Ticket(null,
            $params['title'],
            $params['description'] ?? "",
            null,
            null,
            null);
    }

    /**
     * @throws TitleLengthExceeded
     * @throws InvalidStatusProvided
     * @throws DescriptionLengthExceeded
     */
    public static function updateInMemoryTicket(Ticket $ticket, array $newData)
    {
        $ticket->setTitle(
            self::title($newData['title'] ?? $ticket->getTitle()));
        $ticket->setDescription(
            self::description($newData['description']  ?? $ticket->getDescription()));
        $ticket->setStatus(
            self::status($newData['status']  ?? $ticket->getStatus()));
    }
    /**
     * @throws InvalidStatusProvided
     */
    public static function status(string $status): string
    {
        $validStatus = TicketStatus::allPossibleStatuses();
        if (!in_array(strtolower($status), $validStatus) && !null){
            throw new InvalidStatusProvided(
                "The status provided is not valid. Valid status: "
                . implode(", ", TicketStatus::allPossibleStatuses()));
        }
        if ($status == null){
            $status = TicketStatus::OPEN;
        }
        return $status;
    }

    /**
     * @throws TitleLengthExceeded
     */
    public static function title(string $title): string
    {
        if (strlen($title) > self::MAX_TITLE_LENGTH){
            throw new TitleLengthExceeded();
        }
        return $title;
    }

    /**
     * @throws DescriptionLengthExceeded
     */
    public static function description(string $description): string{
        if (strlen($description) > self::MAX_DESCRIPTION_LENGTH){
            throw new DescriptionLengthExceeded();
        }
        return $description;
    }
}
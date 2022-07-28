<?php

declare(strict_types=1);

namespace App\Domain\Ticket;

use App\Domain\Ticket\TicketExceptions\InvalidStatusProvided;
use App\Domain\Ticket\TicketExceptions\TitleLengthExceeded;
use App\Domain\Ticket\TicketExceptions\TicketTitleMissing;
use JsonSerializable;

class Ticket implements JsonSerializable
{
    private $id;

    private $title;
    private const MAX_TITLE_LENGTH = 100;

    private $description;

    private $status;

    private $date;

    private $last_update;


    public function __construct(?int $id, string $title, string $description, ?string $status, ?string $date, ?string $last_update)
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->status = $status;
        $this->date = $date;
        $this->last_update = $last_update;

    }

    public static function fromArray(array $params): Ticket
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
    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getStatus(): string
    {
        return $this->status;
    }



    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }
    
    /**
     * @throws TitleLengthExceeded
     * @throws InvalidStatusProvided
     */
    public function updateInMemoryTicket(array $newData)
    {
        $this->setTitle(($newData['title'] ?? null)  ? $newData['title'] : $this->title);
        $this->setDescription(($newData['description']  ?? null) ? $newData['description'] : $this->description);
        $this->setStatus(($newData['status']  ?? null) ? $newData['status'] : $this->status );
    }

    /**
     * @param string $status
     * @throws InvalidStatusProvided
     */
    public function setStatus(string $status): void
    {
        $this->status = self::validateStatus($status);

    }
    

    /**
     * @throws InvalidStatusProvided
     */
    private static function validateStatus(string $status): string
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
    private function setTitle($title)
    {
        if (strlen($title) > self::MAX_TITLE_LENGTH){
            throw new TitleLengthExceeded();
        }
        $this->title = $title;
    }


    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'date' => $this->date,
            'last_update' => $this->last_update
        ];
    }


}

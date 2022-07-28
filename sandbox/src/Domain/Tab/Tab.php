<?php

declare(strict_types=1);

namespace App\Domain\Tab;

use App\Domain\Tab\TabExceptions\InvalidTabTickedId;
use App\Domain\Tab\TabExceptions\TabNameLengthExceeded;
use App\Domain\Tab\TabExceptions\TabNameMissing;
use JsonSerializable;

class Tab implements JsonSerializable
{
    private $id;

    private $name;
    private const MAX_NAME_LENGTH = 100;

    private $ticket_id;

    private $date;

    private $last_update;

    public function __construct(?int $id, string $name, int $ticket_id, ?string $date, ?string $last_update)
    {
        $this->id = $id;
        $this->name = $name;
        $this->ticket_id = $ticket_id;
        $this->date = $date;
        $this->last_update = $last_update;

    }

    public static function fromArray(array $params) : Tab
    {
        return new Tab(
            $params['id'],
            $params['name'],
            $params['ticket_id'],
            $params['date'],
            $params['last_update']);
    }


    /**
     * @throws TabNameLengthExceeded
     * @throws InvalidTabTickedId
     * @throws TabNameMissing
     */
    public static function createTabFromArray(array $params): Tab
    {
        self::validateTabParams($params);

        return new Tab(null,
            $params['name'],
            $params['ticket_id'],
            null,
            null);
    }

    /**
     * @throws InvalidTabTickedId
     * @throws TabNameLengthExceeded
     * @throws TabNameMissing
     */
    private static function validateTabParams(array $params){

        if (!array_key_exists('name',$params)){
            throw new TabNameMissing;
        }
        if ($params['name'] == ""){
            throw new TabNameMissing;
        }
        if (strlen($params['name']) > self::MAX_NAME_LENGTH){
            throw new TabNameLengthExceeded;
        }
        if (!array_key_exists('ticket_id',$params)){
            throw new TabNameMissing;
        }
        if (gettype($params['ticket_id']) != "integer") {
            throw new InvalidTabTickedId;
        }
    }


    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'ticket_id' => $this->ticket_id,
            'date' => $this->date,
            'last_update' => $this->last_update
        ];
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTicket_id(): int
    {
        return $this->ticket_id;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @throws TabNameLengthExceeded
     * @throws InvalidTabTickedId
     */
    public function updateInMemoryTab(array $newData)
    {
        $this->setName(($newData['name'] ?? null)  ? $newData['name'] : $this->name);
        $this->setTicketId(($newData['ticket_id'] ?? null)  ? $newData['ticket_id'] : $this->ticket_id);
    }

    /**
     * @throws TabNameLengthExceeded
     */
    private function setName($name)
    {
        if (strlen($name) > self::MAX_NAME_LENGTH){
            throw new TabNameLengthExceeded();
        }
        $this->name = $name;

    }

    /**
     * @throws InvalidTabTickedId
     */
    private function setTicketId($ticked_id)
    {
        if (gettype($ticked_id) != "integer") {
            throw new InvalidTabTickedId;
        }
        $this->ticket_id = $ticked_id;
    }

}
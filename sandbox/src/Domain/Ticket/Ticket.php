<?php

declare(strict_types=1);

namespace App\Domain\Ticket;

use JsonSerializable;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema ()
 */

class Ticket implements JsonSerializable
{
    /**
     * Ticket id,
     * @var int
     * @OA\Property ()
     */
    private $id;

    /**
     * @var string
     * @OA\Property
     */
    private $title;

    /**
     * @var string
     * @OA\Property
     */
    private $description;

    /**
     * @var string|null
     * @OA\Property
     */
    private $status;

    /**
     * @var string|null
     * @OA\Property
     */
    private $date;

    /**
     * @var string|null
     * @OA\Property
     */
    private $last_update;

    /**
     * @param int|null $id
     * @param string $title
     * @param string $description
     * @param string|null $status
     * @param string|null $date
     * @param string|null $last_update
     */
    public function __construct(?int $id, string $title, string $description, ?string $status, ?string $date, ?string $last_update)
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->status = $status;
        $this->date = $date;
        $this->last_update = $last_update;

    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }



    /**
     * @param string $description
     * @return void
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @param string $status
     * @return void
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    /**
     * @param $title
     * @return void
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return array
     */
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

<?php

declare(strict_types=1);

namespace App\Domain\Task;

use App\Domain\Task\TaskExceptions\InvalidTaskSectionId;
use App\Domain\Task\TaskExceptions\TaskNameLengthExceeded;
use App\Domain\Task\TaskExceptions\TaskNameMissing;
use JsonSerializable;

class Task implements JsonSerializable
{
    private $id;

    private $name;
    private const MAX_NAME_LENGTH = 100;

    private $section_id;

    private $status;

    private $is_checked;

    private $is_active;

    private $date;

    private $last_update;

    public function __construct(?int $id, string $name, int $section_id, ?string $status, ?bool $is_checked, ?bool $is_active, ?string $date, ?string $last_update)
    {
        $this->id = $id;
        $this->name = $name;
        $this->section_id = $section_id;
        $this->status = $status;
        $this->is_checked = $is_checked;
        $this->is_active = $is_active;
        $this->date = $date;
        $this->last_update = $last_update;

    }

    public static function fromArray(array $params) : Task
    {
        return new Task(
            $params['id'],
            $params['name'],
            $params['section_id'],
            $params['status'],
            $params['is_checked'],
            $params['is_active'],
            $params['date'],
            $params['last_update']);
    }


    /**
     * @throws TaskNameLengthExceeded
     * @throws InvalidTaskSectionId
     * @throws TaskNameMissing
     */
    public static function createTaskFromArray(array $params): Task
    {
        self::validateTaskParams($params);

        return new Task(null,
            $params['name'],
            $params['section_id'],
            null,
            null,
            null,
            null,
            null);
    }

    /**
     * @throws InvalidTaskSectionId
     * @throws TaskNameLengthExceeded
     * @throws TaskNameMissing
     */
    private static function validateTaskParams(array $params){

        if (!array_key_exists('name',$params)){
            throw new TaskNameMissing;
        }
        if ($params['name'] == ""){
            throw new TaskNameMissing;
        }
        if (strlen($params['name']) > self::MAX_NAME_LENGTH){
            throw new TaskNameLengthExceeded;
        }
        if (!array_key_exists('section_id',$params)){
            throw new TaskNameMissing;
        }
        if (gettype($params['section_id']) != "integer") {
            throw new InvalidTaskSectionId;
        }
    }


    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'section_id' => $this->section_id,
            'date' => $this->date,
            'last_update' => $this->last_update
        ];
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSection_id(): int
    {
        return $this->section_id;
    }

    /**
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @return bool|null
     */
    public function getIsChecked(): ?bool
    {
        return $this->is_checked;
    }

    /**
     * @return bool|null
     */
    public function getIsActive(): ?bool
    {
        return $this->is_active;
    }




    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @throws TaskNameLengthExceeded
     * @throws InvalidTaskSectionId
     */
    public function updateInMemoryTask(array $newData)
    {
        $this->setName(($newData['name'] ?? null)  ? $newData['name'] : $this->name);
        $this->setSectionId(($newData['section_id'] ?? null)  ? $newData['section_id'] : $this->section_id);
    }

    /**
     * @throws TaskNameLengthExceeded
     */
    private function setName($name)
    {
        if (strlen($name) > self::MAX_NAME_LENGTH){
            throw new TaskNameLengthExceeded();
        }
        $this->name = $name;

    }

    /**
     * @throws InvalidTaskSectionId
     */
    private function setSectionId($section_id)
    {
        if (gettype($section_id) != "integer") {
            throw new InvalidTaskSectionId;
        }
        $this->section_id = $section_id;
    }

}
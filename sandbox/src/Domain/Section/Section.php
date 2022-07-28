<?php

namespace App\Domain\Section;

use App\Domain\Section\SectionExceptions\InvalidSectionTabId;
use App\Domain\Section\SectionExceptions\SectionNameLengthExceeded;
use App\Domain\Section\SectionExceptions\SectionNameMissing;
use JsonSerializable;

class Section implements JsonSerializable
{
    private $id;

    private $name;
    private const MAX_NAME_LENGTH = 100;

    private $tab_id;

    private $date;

    private $last_update;

    public function __construct(?int $id, string $name, int $tab_id, ?string $date, ?string $last_update)
    {
        $this->id = $id;
        $this->name = $name;
        $this->tab_id = $tab_id;
        $this->date = $date;
        $this->last_update = $last_update;

    }

    public static function fromArray(array $params) : Section
    {
        return new Section(
            $params['id'],
            $params['name'],
            $params['tab_id'],
            $params['date'],
            $params['last_update']);
    }

    /**
     * @throws SectionNameLengthExceeded
     * @throws InvalidSectionTabId
     * @throws SectionNameMissing
     */
    public static function createSectionFromArray(array $params): Section
    {
        self::validateSectionParams($params);

        return new Section(null,
            $params['name'],
            $params['tab_id'],
            null,
            null);
    }

    /**
     * @throws SectionNameMissing
     * @throws SectionNameLengthExceeded
     * @throws InvalidSectionTabId
     */
    private static function validateSectionParams(array $params){

        if (!array_key_exists('name',$params)){
            throw new SectionNameMissing;
        }
        if ($params['name'] == ""){
            throw new SectionNameMissing;
        }
        if (strlen($params['name']) > self::MAX_NAME_LENGTH){
            throw new SectionNameLengthExceeded;
        }
        if (!array_key_exists('tab_id',$params)){
            throw new SectionNameMissing;
        }
        if (gettype($params['tab_id']) != "integer") {
            throw new InvalidSectionTabId;
        }
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTab_id(): int
    {
        return $this->tab_id;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'tab_id' => $this->tab_id,
            'date' => $this->date,
            'last_update' => $this->last_update
        ];
    }

    /**
     * @throws SectionNameLengthExceeded
     * @throws InvalidSectionTabId
     */
    public function updateInMemorySection(array $newData)
    {
    $this->setName(($newData['name'] ?? null)  ? $newData['name'] : $this->name);
    $this->setTabId(($newData['tab_id'] ?? null)  ? $newData['tab_id'] : $this->tab_id);
    }

    /**
     * @throws SectionNameLengthExceeded
     */
    private function setName($name)
    {
        if (strlen($name) > self::MAX_NAME_LENGTH){
            throw new SectionNameLengthExceeded();
        }
        $this->name = $name;

    }

    /**
     * @throws InvalidSectionTabId
     */
    private function setTabId($tab_id)
    {
        if (gettype($tab_id) != "integer") {
            throw new InvalidSectionTabId();
        }
        $this->tab_id = $tab_id;
    }

}
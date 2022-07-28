<?php

namespace App\Infrastructure\Persistence\Section;

use App\Domain\Section\Section;

use App\Domain\Section\SectionExceptions\SectionNotFoundException;
use App\Domain\Section\SectionRepository;
use PDO;

class MySQLSectionRepository implements SectionRepository
{
    private $conn;

    public function __construct(PDO $connection)
    {
        $this->conn = $connection;
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM sections";
        $stmt = $this->conn->query($sql);

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * @throws SectionNotFoundException
     */
    public function findSectionOfId(int $id): ?Section
    {
        $sql = "SELECT * FROM sections WHERE id=$id";

        $section = $this->conn
            ->query($sql)
            ->fetch(PDO::FETCH_ASSOC);

        //Fetch method returns false if no records where found in database
        if (!$section) {
            throw new SectionNotFoundException();
        } else {
            return Section::fromArray($section);
        }
    }

    public function createNewSection(Section $section): bool
    {
        $sql = "INSERT INTO sections (name, tab_id) VALUES (:name, :tab_id)";

        $data = [
            ':name'=>$section->getName(),
            ':tab_id'=>$section->getTab_id()
        ];

        $stmt = $this->conn ->prepare($sql);

        //Committing data to the database
        return $stmt->execute($data);
    }

    public function updateSection(Section $section): bool
    {
        $tabId = $section->getId();

        $sql = "UPDATE sections set name = :name, tab_id = :tab_id WHERE id = $tabId";


        //Preparing array of data to be persisted
        $data = [
            ':name'=>$section->getName(),
            ':tab_id'=>$section->getTab_id()
        ];

        $stmt = $this->conn ->prepare($sql);

        return $stmt->execute($data);
    }

    public function deleteSectionOfId(int $id): bool
    {
        $sql = "DELETE FROM sections WHERE id = $id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute();    }
}
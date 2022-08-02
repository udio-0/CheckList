<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Tab\Tab;
use App\Domain\Tab\TabExceptions\TabNotFoundException;
use App\Domain\Tab\TabRepositoryInterface;
use PDO;

class TabRepository implements TabRepositoryInterface
{
    private $conn;

    public function __construct(PDO $connection)
    {
        $this->conn = $connection;
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM tabs";
        $stmt = $this->conn->query($sql);

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * @throws TabNotFoundException
     */
    public function findTabById(int $id): ?Tab
    {
        $sql = "SELECT * FROM tabs WHERE id=$id";

        $tab = $this->conn
            ->query($sql)
            ->fetch(PDO::FETCH_ASSOC);

        //Fetch method returns false if no records where found in database
        if (!$tab) {
            throw new TabNotFoundException();
        } else {
            return Tab::fromArray($tab);
        }
    }

    public function createNewTab(Tab $tab): bool
    {
        $sql = "INSERT INTO tabs (name, ticket_id) VALUES (:name, :ticket_id)";

        $data = [
            ':name'=>$tab->getName(),
            ':ticket_id'=>$tab->getTicket_id()
        ];

        $stmt = $this->conn ->prepare($sql);

        //Committing data to the database
        return $stmt->execute($data);
    }

    public function updateTab(Tab $tab): bool
    {
        $tabId = $tab->getId();

        $sql = "UPDATE tabs set name = :name, ticket_id = :ticket_id WHERE id = $tabId";


        //Preparing array of data to be persisted
        $data = [
            ':name'=>$tab->getName(),
            ':ticket_id'=>$tab->getticket_id()
        ];

        $stmt = $this->conn ->prepare($sql);

        return $stmt->execute($data);
    }

    public function deleteTabById(int $id): bool
    {
        $sql = "DELETE FROM tabs WHERE id = $id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute();    }
}
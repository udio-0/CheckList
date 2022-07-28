<?php

namespace App\Infrastructure\Persistence\Ticket;

use App\Domain\Ticket\Ticket;
use App\Domain\Ticket\TicketRepository;
use App\Domain\Ticket\TicketExceptions\TicketNotFoundException;
use PDO;


class MySQLTicketRepository implements TicketRepository
{
    private $conn;

    public function __construct(PDO $connection)
    {
        $this->conn = $connection;
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM tickets";
        $stmt = $this->conn->query($sql);

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }


    /**
     * @throws TicketNotFoundException
     */
    public function findTicketOfId(int $id) : Ticket
    {
        $sql = "SELECT * FROM tickets WHERE id=$id";

        $ticket = $this->conn
            ->query($sql)
            ->fetch(PDO::FETCH_ASSOC);

        //Fetch method returns false if no records where found in database
        if (!$ticket) {
            throw new TicketNotFoundException();
        } else {
            return Ticket::fromArray($ticket);
        }
    }

    public function createNewTicket(Ticket $ticket): bool
    {

        $sql = "INSERT INTO tickets (title, description) VALUES (:title, :description)";

        $data = [
            ':title'=>$ticket->getTitle(),
            ':description'=>$ticket->getDescription()
        ];


        $stmt = $this->conn ->prepare($sql);

        //Committing data to the database
        return $stmt->execute($data);
    }


    public function updateTicket(Ticket $ticket): bool
    {

        $ticketId = $ticket->getId();

        $sql = "UPDATE tickets set title = :title, description = :description, status = :status WHERE id = $ticketId";


        //Preparing array of data to be persisted
        $data = [
            ':title'=>$ticket->getTitle(),
            ':description'=>$ticket->getDescription(),
            ':status'=>$ticket->getStatus()
        ];

        $stmt = $this->conn ->prepare($sql);

        return $stmt->execute($data);
    }

    public function deleteTicketOfId(int $id): bool
    {
        $sql = "DELETE FROM tickets WHERE id = $id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute();
    }
}
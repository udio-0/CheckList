<?php

namespace App\Infrastructure\Persistence\Task;

use App\Domain\Task\Task;
use App\Domain\Task\TaskExceptions\TaskNotFoundException;
use App\Domain\Task\TaskRepository;
use PDO;

class MySQLTaskRepository implements TaskRepository
{
    private $conn;

    public function __construct(PDO $connection)
    {
        $this->conn = $connection;
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM tasks";
        $stmt = $this->conn->query($sql);

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * @throws TaskNotFoundException
     */
    public function findTaskOfId(int $id): ?Task
    {
        $sql = "SELECT * FROM tasks WHERE id=$id";

        $task = $this->conn
            ->query($sql)
            ->fetch(PDO::FETCH_ASSOC);

        //Fetch method returns false if no records where found in database
        if (!$task) {
            throw new TaskNotFoundException();
        } else {
            return Task::fromArray($task);
        }
    }

    public function createNewTask(Task $task): bool
    {
        $sql = "INSERT INTO tasks (name, section_id) VALUES (:name, :section_id)";

        $data = [
            ':name'=>$task->getName(),
            ':section_id'=>$task->getSection_id()
        ];

        $stmt = $this->conn ->prepare($sql);

        //Committing data to the database
        return $stmt->execute($data);
    }

    public function updateTask(Task $task): bool
    {
        $taskId = $task->getId();

        $sql = "UPDATE tasks set name = :name, section_id = :section_id WHERE id = $taskId";


        //Preparing array of data to be persisted
        $data = [
            ':name'=>$task->getName(),
            ':section_id'=>$task->getsection_id()
        ];

        $stmt = $this->conn ->prepare($sql);

        return $stmt->execute($data);
    }

    public function deleteTaskOfId(int $id): bool
    {
        $sql = "DELETE FROM tasks WHERE id = $id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute();    }

}
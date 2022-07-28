<?php

declare(strict_types=1);

namespace App\Domain\Task;

interface TaskRepository
{
    /**
     * @return Task[]
     */
    public function findAll(): array;

    /**
     * @param int $id
     * @return Task
     */
    public function findTaskOfId(int $id): ? Task;

    public function createNewTask(Task $task) : bool;

    public function updateTask(Task $task) : bool;

    public function deleteTaskOfId(int $id): bool;

}
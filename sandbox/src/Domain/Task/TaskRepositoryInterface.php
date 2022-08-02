<?php

declare(strict_types=1);

namespace App\Domain\Task;

interface TaskRepositoryInterface
{
    /**
     * @return Task[]
     */
    public function findAll(): array;

    /**
     * @param int $id
     * @return Task
     */
    public function findTaskById(int $id): ? Task;

    public function createNewTask(Task $task) : bool;

    public function updateTask(Task $task) : bool;

    public function deleteTaskById(int $id): bool;

}
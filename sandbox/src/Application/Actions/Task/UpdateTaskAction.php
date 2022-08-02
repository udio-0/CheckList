<?php

declare(strict_types=1);

namespace App\Application\Actions\Task;

use App\Domain\Task\TaskExceptions\InvalidTaskSectionId;
use App\Domain\Task\TaskExceptions\TaskNameLengthExceeded;
use PDOException;
use Psr\Http\Message\ResponseInterface as Response;

class UpdateTaskAction extends TaskAction
{

    /**
     * @throws TaskNameLengthExceeded
     * @throws InvalidTaskSectionId
     */
    protected function action(): Response
    {
        $taskId = (int) $this->resolveArg('id');

        $task = $this->taskRepository->findTaskById($taskId);

        $task->updateInMemoryTask($this->getFormData());

        try {
            $this->taskRepository->updateTask($task);
        } catch (PDOException $e){
            throw new InvalidTaskSectionId();
        }

        $updatedTask = $this->taskRepository->findTaskById($taskId);

        $this->logger->info("Task of id `${taskId}` was updated.");

        return $this->respondWithData($updatedTask);
    }
}
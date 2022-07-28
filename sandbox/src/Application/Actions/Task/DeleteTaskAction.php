<?php

declare(strict_types=1);

namespace App\Application\Actions\Task;

use Psr\Http\Message\ResponseInterface as Response;

class DeleteTaskAction extends TaskAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $taskId = (int) $this->resolveArg('id');

        $task = $this->taskRepository->findTaskOfId($taskId);

        $taskDeleted = $this->taskRepository->deleteTaskOfId($task->getId());

        $this->logger->info("Task of id `${taskId}` was deleted.");

        return $this->respondWithData($taskDeleted ? "Task was deleted." : "Something went wrong.");
    }

}
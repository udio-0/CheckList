<?php

declare(strict_types=1);

namespace App\Application\Actions\Task;

use Psr\Http\Message\ResponseInterface as Response;

class ViewTaskAction extends TaskAction
{

    protected function action(): Response
    {
        $taskId = (int) $this->resolveArg('id');

        $task = $this->taskRepository->findTaskOfId($taskId);

        $id = $task->getId();

        $this->logger->info("Task of id `${id}` was viewed.");

        return $this->respondWithData($task);
    }
}
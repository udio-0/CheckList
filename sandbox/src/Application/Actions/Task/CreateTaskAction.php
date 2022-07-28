<?php

declare(strict_types=1);

namespace App\Application\Actions\Task;

use App\Domain\Task\Task;
use App\Domain\Task\TaskExceptions\InvalidTaskSectionId;
use App\Domain\Task\TaskExceptions\TaskNameMissing;
use App\Domain\Task\TaskExceptions\TaskNameLengthExceeded;
use Psr\Http\Message\ResponseInterface as Response;

class CreateTaskAction extends TaskAction
{
    /**
     * @throws TaskNameMissing
     * @throws TaskNameLengthExceeded
     * @throws InvalidTaskSectionId
     */
    protected function action(): Response
    {

        $task = Task::createTaskFromArray($this->getFormData());

        $isTaskCreated = $this->taskRepository->createNewTask($task);

        if (!$isTaskCreated){
            throw new InvalidTaskSectionId();
        }

        $this->logger->info("New Task added.");

        return $this->respondWithData("New Task was added.");
    }



}
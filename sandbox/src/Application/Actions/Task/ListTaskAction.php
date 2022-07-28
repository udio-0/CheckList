<?php

declare(strict_types=1);

namespace App\Application\Actions\Task;

use Psr\Http\Message\ResponseInterface as Response;

class ListTaskAction extends TaskAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $this->logger->info("Tasks list was viewed.");

        $tasks = $this->taskRepository->findAll();

        return $this->respondWithData($tasks);
    }

}
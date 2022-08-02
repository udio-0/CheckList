<?php

declare(strict_types=1);

namespace App\Application\Actions\Task;

use App\Application\Actions\Action;
use App\Domain\Task\TaskRepositoryInterface;
use Psr\Log\LoggerInterface;

abstract class TaskAction extends Action
{
    protected $taskRepository;

    public function __construct(LoggerInterface $logger, TaskRepositoryInterface $ticketRepository)
    {
        parent::__construct($logger);
        $this->taskRepository = $ticketRepository;
    }
}
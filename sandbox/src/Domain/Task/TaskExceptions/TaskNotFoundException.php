<?php

declare(strict_types=1);

namespace App\Domain\Task\TaskExceptions;

use App\Domain\DomainException\DomainRecordNotFoundException;

class TaskNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'The task you requested does not exist.';

}
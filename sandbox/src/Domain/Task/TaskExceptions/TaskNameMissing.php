<?php

declare(strict_types=1);

namespace App\Domain\Task\TaskExceptions;

use App\Domain\DomainException\DomainRecordNotFoundException;

class TaskNameMissing extends DomainRecordNotFoundException
{
    public $message = 'Task cannot be created if "Name" is missing or empty.';

}
<?php

declare(strict_types=1);

namespace App\Domain\Task\TaskExceptions;

use App\Domain\DomainException\DomainException;

class TaskNameLengthExceeded extends DomainException
{
    public $message = "The length of the Name exceeded 100 characters.";
}
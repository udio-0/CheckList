<?php

declare(strict_types=1);

namespace App\Domain\Task\TaskExceptions;

use App\Domain\DomainException\DomainException;

class InvalidTaskSectionId extends DomainException
{
    public $message = "The section id given is not valid or does not exist.";
}
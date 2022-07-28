<?php

declare(strict_types=1);

use App\Domain\Section\SectionRepository;
use App\Domain\Tab\TabRepository;
use App\Domain\Task\TaskRepository;
use App\Domain\Ticket\TicketRepository;
use App\Domain\User\UserRepository;
use App\Infrastructure\Persistence\Section\MySQLSectionRepository;
use App\Infrastructure\Persistence\Tab\MySQLTabRepository;
use App\Infrastructure\Persistence\Ticket\MySQLTicketRepository;
use App\Infrastructure\Persistence\Task\MySQLTaskRepository;
use App\Infrastructure\Persistence\User\InMemoryUserRepository;
use DI\ContainerBuilder;
use function DI\autowire;

return function (ContainerBuilder $containerBuilder) {
    // Here we map our UserRepository interface to its in memory implementation
    $containerBuilder->addDefinitions([
        UserRepository::class => autowire(InMemoryUserRepository::class),
        TicketRepository::class => autowire(MySQLTicketRepository::class),
        TabRepository::class => autowire(MySQLTabRepository::class),
        SectionRepository::class => autowire(MySQLSectionRepository::class),
        TaskRepository::class => autowire(MySQLTaskRepository::class)
    ]);
};

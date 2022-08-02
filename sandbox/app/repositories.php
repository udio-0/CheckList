<?php

declare(strict_types=1);

use App\Domain\Section\SectionRepositoryInterface;
use App\Domain\Tab\TabRepositoryInterface;
use App\Domain\Task\TaskRepositoryInterface;
use App\Domain\Ticket\TicketRepositoryInterface;
use App\Domain\User\UserRepository;
use App\Infrastructure\Persistence\SectionRepository;
use App\Infrastructure\Persistence\TabRepository;
use App\Infrastructure\Persistence\TicketRepository;
use App\Infrastructure\Persistence\TaskRepository;
use App\Infrastructure\Persistence\User\InMemoryUserRepository;
use DI\ContainerBuilder;
use function DI\autowire;

return function (ContainerBuilder $containerBuilder) {
    // Here we map our UserRepository interface to its in memory implementation
    $containerBuilder->addDefinitions([
        UserRepository::class => autowire(InMemoryUserRepository::class),
        TicketRepositoryInterface::class => autowire(TicketRepository::class),
        TabRepositoryInterface::class => autowire(TabRepository::class),
        SectionRepositoryInterface::class => autowire(SectionRepository::class),
        TaskRepositoryInterface::class => autowire(TaskRepository::class)
    ]);
};

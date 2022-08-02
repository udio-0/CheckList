<?php

declare(strict_types=1);

namespace App\Application\Actions\Ticket;

use App\Application\Actions\Action;
use App\Domain\Ticket\TicketRepositoryInterface;
use Psr\Log\LoggerInterface;

abstract class TicketAction extends Action
{
    /**
     * @var TicketRepositoryInterface
     */
    protected $ticketRepository;

    /**
     * @param LoggerInterface $logger
     * @param TicketRepositoryInterface $ticketRepository
     */
    public function __construct(LoggerInterface $logger, TicketRepositoryInterface $ticketRepository)
    {
        parent::__construct($logger);
        $this->ticketRepository = $ticketRepository;
    }
}

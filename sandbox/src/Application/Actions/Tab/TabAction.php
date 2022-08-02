<?php

declare(strict_types=1);

namespace App\Application\Actions\Tab;

use App\Application\Actions\Action;
use App\Domain\Tab\TabRepositoryInterface;
use Psr\Log\LoggerInterface;

abstract class TabAction extends Action
{
    protected $tabRepository;

    public function __construct(LoggerInterface $logger, TabRepositoryInterface $ticketRepository)
    {
        parent::__construct($logger);
        $this->tabRepository = $ticketRepository;
    }
}
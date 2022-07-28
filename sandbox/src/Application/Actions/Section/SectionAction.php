<?php

declare(strict_types=1);

namespace App\Application\Actions\Section;

use App\Application\Actions\Action;
use App\Domain\Section\SectionRepository;
use Psr\Log\LoggerInterface;

abstract class SectionAction extends Action
{
    protected $sectionRepository;

    public function __construct(LoggerInterface $logger, SectionRepository $ticketRepository)
    {
        parent::__construct($logger);
        $this->sectionRepository = $ticketRepository;
    }
}
<?php

declare(strict_types=1);

namespace App\Application\Actions\Tab;

use Psr\Http\Message\ResponseInterface as Response;

class ListTabAction extends TabAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $this->logger->info("Tabs list was viewed.");

        $tabs = $this->tabRepository->findAll();

        return $this->respondWithData($tabs);
    }

}
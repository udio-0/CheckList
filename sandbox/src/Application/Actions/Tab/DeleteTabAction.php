<?php

declare(strict_types=1);

namespace App\Application\Actions\Tab;

use Psr\Http\Message\ResponseInterface as Response;

class DeleteTabAction extends TabAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $tabId = (int) $this->resolveArg('id');

        $tab = $this->tabRepository->findTabById($tabId);

        $tabDeleted = $this->tabRepository->deleteTabById($tab->getId());

        $this->logger->info("Tab of id `${tabId}` was deleted.");

        return $this->respondWithData($tabDeleted ? "Tab was deleted." : "Something went wrong.");
    }

}
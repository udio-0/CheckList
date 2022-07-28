<?php

declare(strict_types=1);

namespace App\Application\Actions\Tab;

use Psr\Http\Message\ResponseInterface as Response;

class ViewTabAction extends TabAction
{

    protected function action(): Response
    {
        $tabId = (int) $this->resolveArg('id');

        $tab = $this->tabRepository->findTabOfId($tabId);

        $id = $tab->getId();

        $this->logger->info("Tab of id `${id}` was viewed.");

        return $this->respondWithData($tab);
    }
}
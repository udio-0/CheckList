<?php

declare(strict_types=1);

namespace App\Application\Actions\Tab;

use App\Domain\Tab\TabExceptions\InvalidTabTickedId;
use App\Domain\Tab\TabExceptions\TabNameLengthExceeded;
use PDOException;
use Psr\Http\Message\ResponseInterface as Response;

class UpdateTabAction extends TabAction
{

    /**
     * @throws TabNameLengthExceeded
     * @throws InvalidTabTickedId
     */
    protected function action(): Response
    {
        $tabId = (int) $this->resolveArg('id');

        $tab = $this->tabRepository->findTabOfId($tabId);

        $tab->updateInMemoryTab($this->getFormData());

        try {
            $this->tabRepository->updateTab($tab);
        } catch (PDOException $e){
            throw new InvalidTabTickedId();
        }

        $updatedTab = $this->tabRepository->findTabOfId($tabId);

        $this->logger->info("Tab of id `${tabId}` was updated.");

        return $this->respondWithData($updatedTab);
    }
}
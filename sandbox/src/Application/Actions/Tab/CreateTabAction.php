<?php

declare(strict_types=1);

namespace App\Application\Actions\Tab;

use App\Domain\Tab\Tab;
use App\Domain\Tab\TabExceptions\InvalidTabTickedId;
use App\Domain\Tab\TabExceptions\TabNameMissing;
use App\Domain\Tab\TabExceptions\TabNameLengthExceeded;
use Psr\Http\Message\ResponseInterface as Response;

class CreateTabAction extends TabAction
{
    /**
     * @throws TabNameMissing
     * @throws TabNameLengthExceeded
     * @throws InvalidTabTickedId
     */
    protected function action(): Response
    {

        $tab = Tab::createTabFromArray($this->getFormData());

        $isTabCreated = $this->tabRepository->createNewTab($tab);

        if (!$isTabCreated){
            throw new InvalidTabTickedId();
        }

        $this->logger->info("New Tab added.");

        return $this->respondWithData("New Tab was added.");
    }



}
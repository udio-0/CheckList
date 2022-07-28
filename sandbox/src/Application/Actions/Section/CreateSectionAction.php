<?php

declare(strict_types=1);

namespace App\Application\Actions\Section;

use App\Domain\Section\Section;
use App\Domain\Section\SectionExceptions\InvalidSectionTabId;
use App\Domain\Section\SectionExceptions\SectionNameMissing;
use App\Domain\Section\SectionExceptions\SectionNameLengthExceeded;
use Psr\Http\Message\ResponseInterface as Response;

class CreateSectionAction extends SectionAction
{
    /**
     * @throws SectionNameMissing
     * @throws SectionNameLengthExceeded
     * @throws InvalidSectionTabId
     */
    protected function action(): Response
    {

        $Section = Section::createSectionFromArray($this->getFormData());

        $isSectionCreated = $this->sectionRepository->createNewSection($Section);

        if (!$isSectionCreated){
            throw new InvalidSectionTabId();
        }

        $this->logger->info("New Section added.");

        return $this->respondWithData("New Section was added.");
    }



}
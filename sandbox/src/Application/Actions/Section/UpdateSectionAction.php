<?php

declare(strict_types=1);

namespace App\Application\Actions\Section;

use App\Domain\Section\SectionExceptions\InvalidSectionTabId;
use App\Domain\Section\SectionExceptions\SectionNameLengthExceeded;
use PDOException;
use Psr\Http\Message\ResponseInterface as Response;

class UpdateSectionAction extends SectionAction
{

    /**
     * @throws SectionNameLengthExceeded
     * @throws InvalidSectionTabId
     */
    protected function action(): Response
    {
        $sectionId = (int) $this->resolveArg('id');

        $section = $this->sectionRepository->findSectionById($sectionId);

        $section->updateInMemorySection($this->getFormData());

        try {
            $this->sectionRepository->updateSection($section);
        } catch (PDOException $e){
            throw new InvalidSectionTabId();
        }

        $updatedSection = $this->sectionRepository->findSectionById($sectionId);

        $this->logger->info("Section of id `${sectionId}` was updated.");

        return $this->respondWithData($updatedSection);
    }
}
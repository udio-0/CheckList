<?php

declare(strict_types=1);

namespace App\Application\Actions\Section;

use Psr\Http\Message\ResponseInterface as Response;

class DeleteSectionAction extends SectionAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $sectionId = (int) $this->resolveArg('id');

        $section = $this->sectionRepository->findSectionOfId($sectionId);

        $sectionDeleted = $this->sectionRepository->deleteSectionOfId($section->getId());

        $this->logger->info("Section of id `${sectionId}` was deleted.");

        return $this->respondWithData($sectionDeleted ? "Section was deleted." : "Something went wrong.");
    }

}
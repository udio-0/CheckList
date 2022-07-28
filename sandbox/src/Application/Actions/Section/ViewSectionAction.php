<?php

declare(strict_types=1);

namespace App\Application\Actions\Section;

use Psr\Http\Message\ResponseInterface as Response;

class ViewSectionAction extends SectionAction
{

    protected function action(): Response
    {
        $sectionId = (int) $this->resolveArg('id');

        $section = $this->sectionRepository->findSectionOfId($sectionId);

        $id = $section->getId();

        $this->logger->info("Section of id `${id}` was viewed.");

        return $this->respondWithData($section);
    }
}
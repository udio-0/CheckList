<?php

declare(strict_types=1);

namespace App\Domain\Section;

interface SectionRepository
{
    /**
     * @return Section[]
     */
    public function findAll(): array;

    /**
     * @param int $id
     * @return Section
     */
    public function findSectionOfId(int $id): ? Section;

    public function createNewSection(Section $section) : bool;

    public function updateSection(Section $section) : bool;

    public function deleteSectionOfId(int $id): bool;

}
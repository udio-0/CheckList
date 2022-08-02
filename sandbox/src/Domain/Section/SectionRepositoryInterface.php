<?php

declare(strict_types=1);

namespace App\Domain\Section;

interface SectionRepositoryInterface
{
    /**
     * @return Section[]
     */
    public function findAll(): array;

    /**
     * @param int $id
     * @return Section
     */
    public function findSectionById(int $id): ? Section;

    public function createNewSection(Section $section) : bool;

    public function updateSection(Section $section) : bool;

    public function deleteSectionById(int $id): bool;

}
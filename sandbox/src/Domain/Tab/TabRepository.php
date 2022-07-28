<?php

declare(strict_types=1);

namespace App\Domain\Tab;

interface TabRepository
{
    /**
     * @return Tab[]
     */
    public function findAll(): array;

    /**
     * @param int $id
     * @return Tab
     */
    public function findTabOfId(int $id): ? Tab;

    public function createNewTab(Tab $tab) : bool;

    public function updateTab(Tab $tab) : bool;

    public function deleteTabOfId(int $id): bool;

}
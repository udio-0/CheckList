<?php

declare(strict_types=1);

namespace App\Domain\Tab;

interface TabRepositoryInterface
{
    /**
     * @return Tab[]
     */
    public function findAll(): array;

    /**
     * @param int $id
     * @return Tab
     */
    public function findTabById(int $id): ? Tab;

    public function createNewTab(Tab $tab) : bool;

    public function updateTab(Tab $tab) : bool;

    public function deleteTabById(int $id): bool;

}
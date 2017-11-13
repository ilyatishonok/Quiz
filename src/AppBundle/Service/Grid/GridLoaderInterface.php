<?php

declare(strict_types=1);

namespace AppBundle\Service\Grid;

interface GridLoaderInterface
{
    public function loadGrid(array $tableFields): GridLoaderInterface;

    public function getTemplate(): string;

    public function getViewData(): array;
}
<?php
/**
 * Created by PhpStorm.
 * User: Ilixi
 * Date: 09.11.2017
 * Time: 18:32
 */

namespace AppBundle\Service\Grid;


interface GridLoaderInterface
{
    public function loadGrid(array $tableFields): GridLoaderInterface;

    public function getTemplate(): string;

    public function getViewData(): array;
}
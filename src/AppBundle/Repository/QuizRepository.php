<?php

declare(strict_types=1);

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class QuizRepository extends EntityRepository
{
    public function createLoaderQueryByName(string $name): Query
    {
        return $this->createQueryBuilder("quiz")
            ->where("quiz.name LIKE :name")
            ->setParameter("name",$name."%")
            ->getQuery();
    }

    public function createLoaderQuery(): Query
    {
        return $this->createQueryBuilder("quiz")
            ->getQuery();
    }


}

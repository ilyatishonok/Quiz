<?php

declare(strict_types=1);

namespace AppBundle\Repository;

use Doctrine\ORM\Query;

class QuizRepository extends \Doctrine\ORM\EntityRepository
{
    public function createQueryByName(string $name): Query
    {
        return $this->createQueryBuilder("quiz")
            ->where("quiz.name LIKE :name")
            ->setParameter("name",$name."%")
            ->getQuery();
    }

    public function createQuery(): Query
    {
        return $this->createQueryBuilder("quiz")
            ->getQuery();
    }


}

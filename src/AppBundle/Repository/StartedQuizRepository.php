<?php

declare(strict_types=1);

namespace AppBundle\Repository;

use AppBundle\Entity\UserInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class StartedQuizRepository extends EntityRepository
{
    public function createLoaderQueryByUserAndName(UserInterface $user, string $name): Query
    {
        return $this->createQueryBuilder("sq")
            ->select("sq","q")
            ->leftJoin("sq.quiz", "q")
            ->where("sq.user = :user")
            ->andWhere("q.name LIKE :name")
            ->setParameters(array(
                "user" => $user,
                "name" => $name."%",
            ))
            ->getQuery();
    }

    public function createLoaderQueryByUser(UserInterface $user): Query
    {
        return $this->createQueryBuilder("sq")
            ->select("sq","q")
            ->leftJoin("sq.quiz","q")
            ->where("sq.user = :user")
            ->setParameter("user", $user)
            ->getQuery();
    }
}

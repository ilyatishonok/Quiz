<?php

declare(strict_types=1);

namespace AppBundle\Repository;

class CompletedQuizRepository extends \Doctrine\ORM\EntityRepository
{
    public function loadUserPosition($rightQuestions, $time)
    {
        return $this->createQueryBuilder("u")
            ->select("COUNT(u.id)")
            ->where("u.rightQuestions > :right")
            ->andWhere("u.time > :time")
            ->orderBy("u.time", "ASC")
            ->setParameters(array('right'=>$rightQuestions, "time"=>$time))
            ->getQuery()
            ->getSingleScalarResult();
    }
}

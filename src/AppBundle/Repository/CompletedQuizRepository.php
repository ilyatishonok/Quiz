<?php

declare(strict_types=1);

namespace AppBundle\Repository;

use AppBundle\Entity\Quiz;

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


    public function loadLeaders(Quiz $quiz)
    {
        return $this->createQueryBuilder("cq")
            ->where("cq.quiz = :quiz")
            ->leftJoin('cq.user',"u")
            ->orderBy("cq.rightQuestions","DESC")
            ->addOrderBy("cq.time", "ASC")
            ->setParameter("quiz", $quiz)
            ->setMaxResults(3)
            ->getQuery()
            ->getResult();
    }
}

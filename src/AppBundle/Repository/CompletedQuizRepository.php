<?php

declare(strict_types=1);

namespace AppBundle\Repository;

use AppBundle\Entity\CompletedQuiz;
use AppBundle\Entity\Quiz;
use AppBundle\Entity\UserInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Query;

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

    public function loadLeader(Quiz $quiz): CompletedQuiz
    {
        return $this->createQueryBuilder("c")
            ->select("c")
            ->leftJoin("c.user","u")
            ->where("c.quiz = :quiz")
            ->andWhere("u.username = :username")
            ->orderBy("c.rightQuestions", "DESC")
            ->addOrderBy("c.time", "ASC")
            ->setParameters(array("quiz"=>$quiz,"username"=>$quiz->getLeader()))
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function createLoaderQuery(UserInterface $user): Query
    {
        return $this->createQueryBuilder("c")
            ->select("c","q")
            ->leftJoin("c.quiz", "q")
            ->where("c.user = :user")
            ->setParameter("user", $user)
            ->getQuery();
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

<?php

declare(strict_types=1);

namespace AppBundle\Repository;

use AppBundle\Entity\CompletedQuiz;
use AppBundle\Entity\Quiz;
use AppBundle\Entity\UserInterface;
use Doctrine\ORM\Query;

class CompletedQuizRepository extends \Doctrine\ORM\EntityRepository
{
    //Todo refactore this class!
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
        return $this->createQueryBuilder("cq")
            ->select("cq")
            ->leftJoin("cq.user","u")
            ->where("cq.quiz = :quiz")
            ->andWhere("u.username = :username")
            ->orderBy("cq.rightQuestions", "DESC")
            ->addOrderBy("cq.time", "ASC")
            ->setParameters(array("quiz"=>$quiz,"username"=>$quiz->getLeader()))
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function createLoaderQueryByUser(UserInterface $user): Query
    {
        return $this->createQueryBuilder("cq")
            ->select("cq","q")
            ->leftJoin("cq.quiz", "q")
            ->where("cq.user = :user")
            ->setParameter("user", $user)
            ->getQuery();
    }

    public function createLoaderQueryByUserAndName(UserInterface $user, string $name): Query
    {
        return $this->createQueryBuilder("cq")
            ->select("cq", "q")
            ->leftJoin("cq.quiz", "q")
            ->where("cq.user = :user")
            ->andWhere("q.name LIKE :name")
            ->setParameters(array(
                "user" => $user,
                "name" => $name."%",
            ))
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

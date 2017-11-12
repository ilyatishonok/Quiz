<?php

declare(strict_types=1);

namespace AppBundle\Repository;

use AppBundle\Entity\CompletedQuiz;
use AppBundle\Entity\Quiz;
use AppBundle\Entity\UserInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class CompletedQuizRepository extends EntityRepository
{
    public function loadUserPosition(int $rightQuestions, float $seconds): string
    {
        return $this->createQueryBuilder("u")
            ->select("COUNT(u.id)")
            ->andWhere("u.rightQuestions > :right OR (u.rightQuestions = :right AND u.seconds < :seconds)")
            ->setParameters(array('right' => $rightQuestions, "seconds" => $seconds))
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function loadLeader(Quiz $quiz): ?CompletedQuiz
    {
        return $this->createQueryBuilder("cq")
            ->select("cq", "u")
            ->leftJoin("cq.user","u")
            ->where("cq.quiz = :quiz")
            ->andWhere("u.username = :username")
            ->setParameters(array("quiz" => $quiz,"username" => $quiz->getLeader()))
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


    public function loadLeaders(Quiz $quiz): array
    {
        return $this->createQueryBuilder("cq")
            ->select("cq", "u")
            ->where("cq.quiz = :quiz")
            ->leftJoin('cq.user',"u")
            ->orderBy("cq.rightQuestions","DESC")
            ->addOrderBy("cq.seconds", "ASC")
            ->setParameter("quiz", $quiz)
            ->setMaxResults(3)
            ->getQuery()
            ->getResult();
    }
}

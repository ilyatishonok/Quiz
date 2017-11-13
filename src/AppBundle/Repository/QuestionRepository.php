<?php

declare(strict_types=1);

namespace AppBundle\Repository;

use AppBundle\Entity\Question;
use Doctrine\ORM\EntityRepository;

class QuestionRepository extends EntityRepository
{
    public function loadQuestionsByRegular(string $regular): array
    {
        return $this->createQueryBuilder("q")
            ->where('q.name LIKE :regular')
            ->setParameter("regular",$regular."%")
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
    }

    public function loadQuestionWithAnswersByName(string $name): Question
    {
        return $this->createQueryBuilder("q")
            ->leftJoin("q.answers", 'a')
            ->where("q.name=:name")
            ->setParameter('name',$name)
            ->getQuery()
            ->getOneOrNullResult();
    }


    public function loadQuestionWithAnswers($id): Question
    {
        return $this->createQueryBuilder("q")
            ->select("q", "a")
            ->leftJoin("q.answers", 'a')
            ->where("q.id=:id")
            ->setParameter('id',$id)
            ->getQuery()
            ->getOneOrNullResult();
    }
}

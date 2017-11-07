<?php

declare(strict_types=1);

namespace AppBundle\Repository;

use AppBundle\Entity\WiredQuestion;

class WiredQuestionRepository extends \Doctrine\ORM\EntityRepository
{
    public function loadQuestion(int $questionId): WiredQuestion
    {
        return $this->createQueryBuilder("wired")
            ->leftJoin("wired.question","question")
            ->leftJoin("wired.quiz","quiz")
            ->where("wired.id = :id")
            ->setParameter("id", $questionId)
            ->getQuery()
            ->getOneOrNullResult();
    }
}

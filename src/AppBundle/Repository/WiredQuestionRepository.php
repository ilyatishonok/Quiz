<?php

declare(strict_types=1);

namespace AppBundle\Repository;

use AppBundle\Entity\Quiz;
use AppBundle\Entity\WiredQuestion;

class WiredQuestionRepository extends \Doctrine\ORM\EntityRepository
{
    public function loadQuestion(int $questionId): ?WiredQuestion
    {
        return $this->createQueryBuilder("wired")
            ->leftJoin("wired.question","question")
            ->leftJoin("wired.quiz","quiz")
            ->where("wired.id = :id")
            ->setParameter("id", $questionId)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function loadByQuizAndNumber(Quiz $quiz, int $position): ?WiredQuestion
    {
        return $this->createQueryBuilder("wired")
            ->select("wired", "question", "answers")
            ->leftJoin("wired.question", "question")
            ->leftJoin("question.answers", "answers")
            ->where("wired.questionNumber = :position")
            ->andWhere("wired.quiz = :quiz")
            ->setParameters(array(
                "position"=>$position,
                "quiz"=>$quiz
                ))
            ->getQuery()
            ->getOneOrNullResult();
    }
}

<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Question;
use AppBundle\Entity\Quiz;

class QuizRepository extends \Doctrine\ORM\EntityRepository
{
    public function createQuiz(string $name, array $questionIds) {
        $entityManager = $this->getEntityManager();
        $quiz = new Quiz();
        $quiz->setName($name);
        $quiz->setCountOfQuestions(count($questionIds));
        $quiz->setCountOfPlayers(0);
        foreach ($questionIds as $id){
            $question = $entityManager->getReference("AppBundle\Entity\Question", $id);
            $quiz->addQuestion($question);
        }

        $entityManager->persist($quiz);
        $entityManager->flush();

        return $quiz;
    }

}

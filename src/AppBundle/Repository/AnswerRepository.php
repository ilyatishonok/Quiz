<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Answer;


class AnswerRepository extends \Doctrine\ORM\EntityRepository
{
    public function saveAnswers(array $answers, $question) : void
    {
        $entityManager = $this->getEntityManager();
        foreach ($answers as $name=>$isCorrect){
            $newAnswer = new Answer();
            $newAnswer->setQuestion($question);
            $newAnswer->setName($name);
            $newAnswer->setIsCorrect($isCorrect);

            $entityManager->persist($newAnswer);
        }

        $entityManager->flush();
    }
}

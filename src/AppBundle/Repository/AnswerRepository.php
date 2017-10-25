<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Answer;
use AppBundle\Entity\Question;
use Doctrine\Common\Collections\ArrayCollection;


class AnswerRepository extends \Doctrine\ORM\EntityRepository
{
    public function createAnswers(array $answers, Question $question): ArrayCollection
    {
        $entityManager = $this->getEntityManager();

        $answerCollectin = new ArrayCollection();

        foreach ($answers as $name=>$isCorrect){
            $newAnswer = new Answer();
            $newAnswer->setQuestion($question);
            $newAnswer->setName($name);
            $newAnswer->setIsCorrect($isCorrect);

            $answerCollectin->add($newAnswer);

            $entityManager->persist($newAnswer);
        }
        return $answerCollectin;
    }
}

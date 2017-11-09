<?php

namespace AppBundle\Service\Answer;

use AppBundle\Exceptions\AnswerException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;

class AnswerManager implements AnswerManagerInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function correctAnswers(Collection $answers, string $selectedAnswerName): void
    {
        $isExist = false;

        foreach ($answers as $answer){
            if($answer->getName() === $selectedAnswerName)
            {
                $isExist = true;
                $answer->setIsCorrect(true);
            }
            if(!$answer->getName())
            {
                throw new AnswerException("Incorrect answer data!");
            }
        }

        if(!$isExist)
        {
            throw new AnswerException("Answer was not selected!");
        }
    }

    public function getNames(Collection $answers): array
    {
        $answerNames = array();
        foreach ($answers as $answer){
            $answerNames[$answer->getName()] = $answer->isCorrect();
        }

        return $answerNames;
    }
}
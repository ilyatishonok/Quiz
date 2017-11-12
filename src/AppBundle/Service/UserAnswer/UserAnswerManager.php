<?php

declare(strict_types=1);

namespace AppBundle\Service\UserAnswer;

use AppBundle\Entity\Quiz;
use AppBundle\Entity\UserAnswer;
use AppBundle\Entity\UserInterface;
use Doctrine\ORM\EntityManagerInterface;

class UserAnswerManager implements UserAnswerManagerInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createUserAnswer(UserInterface $user, Quiz $quiz, int $questionNumber, bool $isCorrect, bool $isFlush = false): UserAnswer
    {
        $userAnswer = new UserAnswer();
        $userAnswer->setUser($user);
        $userAnswer->setQuiz($quiz);
        $userAnswer->setQuestionNumber($questionNumber);
        $userAnswer->setCorrect($isCorrect);

        if ($isFlush) {
            $this->entityManager->persist($userAnswer);
            $this->entityManager->flush();
        }

        return $userAnswer;
    }
}

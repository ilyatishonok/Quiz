<?php

declare(strict_types=1);

namespace AppBundle\Service\StartedQuiz;

use AppBundle\Entity\Quiz;
use AppBundle\Entity\StartedQuiz;
use AppBundle\Entity\UserInterface;
use Doctrine\ORM\EntityManagerInterface;

class StartedQuizManager implements StartedQuizManagerInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createStartedQuiz(UserInterface $user, Quiz $quiz, bool $isFlush = false): StartedQuiz
    {
        $startedQuiz = new StartedQuiz();
        $startedQuiz->setQuiz($quiz);
        $startedQuiz->setUser($user);
        $startedQuiz->setStartTime(new \DateTime());

        if ($isFlush) {
            $this->entityManager->persist($startedQuiz);
            $this->entityManager->flush();
        }

        return $startedQuiz;
    }

    public function updateStartedQuiz(StartedQuiz $quiz, int $questionNumber, bool $isCorrect, bool $isFlush = false): StartedQuiz
    {
        $quiz->setLastQuestionNumber($questionNumber+1);

        if ($isCorrect) {
            $quiz->addRightAnswer();
        }

        if ($isFlush) {
            $this->entityManager->flush();
        }

        return $quiz;
    }
}

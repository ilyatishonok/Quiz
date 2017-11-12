<?php

declare(strict_types=1);

namespace AppBundle\Service\CompletedQuiz;

use AppBundle\Entity\CompletedQuiz;
use AppBundle\Entity\StartedQuiz;
use AppBundle\Entity\UserInterface;
use AppBundle\Service\DateIntervalHandler\DateIntervalHandler;
use Doctrine\ORM\EntityManagerInterface;

class CompletedQuizManager implements CompletedQuizManagerInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createCompletedQuiz(UserInterface $user, StartedQuiz $startedQuiz, int $rightQuestions, bool $isFlush = false): CompletedQuiz
    {
        $completedQuiz = new CompletedQuiz();
        $completedQuiz->setQuiz($startedQuiz->getQuiz());
        $completedQuiz->setUser($user);
        $completedQuiz->setRightQuestions($rightQuestions);
        $completedQuiz->setStartTime($startedQuiz->getStartTime());

        $dateIntervalHandler = new DateIntervalHandler($completedQuiz->getEndTime()->diff($completedQuiz->getStartTime()));

        $completedQuiz->setSeconds($dateIntervalHandler->getSeconds());

        if ($isFlush) {
           $this->entityManager->persist($completedQuiz);
           $this->entityManager->flush();
        }

        return $completedQuiz;
    }
}

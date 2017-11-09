<?php

declare(strict_types=1);

namespace AppBundle\Service\CompletedQuiz;

use AppBundle\Entity\CompletedQuiz;
use AppBundle\Entity\StartedQuiz;
use AppBundle\Entity\UserInterface;

class CompletedQuizManager implements CompletedQuizManagerInterface
{
    public function createCompletedQuiz(UserInterface $user, StartedQuiz $startedQuiz, int $rightQuestions): CompletedQuiz
    {
        $completedQuiz = new CompletedQuiz();
        $completedQuiz->setQuiz($startedQuiz->getQuiz());
        $completedQuiz->setUser($user);
        $completedQuiz->setRightQuestions($rightQuestions);

        $endTime = new \DateTime();
        $completedQuiz->setTime($endTime->diff($startedQuiz->getStartTime()));
        return $completedQuiz;
    }
}

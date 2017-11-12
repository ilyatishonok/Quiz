<?php

declare(strict_types=1);

namespace AppBundle\Service\CompletedQuiz;

use AppBundle\Entity\CompletedQuiz;
use AppBundle\Entity\StartedQuiz;
use AppBundle\Entity\UserInterface;

interface CompletedQuizManagerInterface
{
    public function createCompletedQuiz(UserInterface $user, StartedQuiz $startedQuiz, int $rightQuestions, bool $isFlush = false): CompletedQuiz;
}

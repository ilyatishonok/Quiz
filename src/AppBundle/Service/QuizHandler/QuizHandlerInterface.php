<?php

declare(strict_types=1);

namespace AppBundle\Service\QuizHandler;

use AppBundle\Entity\StartedQuiz;
use AppBundle\Entity\UserInterface;
use AppBundle\Entity\WiredQuestion;

interface QuizHandlerInterface
{
    public function handleProcessQuiz(UserInterface $user, StartedQuiz $quiz, WiredQuestion $question, bool $isCorrect): void;
}

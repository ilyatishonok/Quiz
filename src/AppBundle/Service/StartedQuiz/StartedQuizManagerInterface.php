<?php

declare(strict_types=1);

namespace AppBundle\Service\StartedQuiz;

use AppBundle\Entity\Quiz;
use AppBundle\Entity\StartedQuiz;
use AppBundle\Entity\UserInterface;

interface StartedQuizManagerInterface
{
    public function createStartedQuiz(UserInterface $user, Quiz $quiz, bool $isFlush = false): StartedQuiz;

    public function updateStartedQuiz(StartedQuiz $quiz, int $questionNumber, bool $isCorrect, bool $isFlush = false): StartedQuiz;
}

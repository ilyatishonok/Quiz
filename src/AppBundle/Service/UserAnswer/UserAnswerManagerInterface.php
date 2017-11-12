<?php

declare(strict_types=1);

namespace AppBundle\Service\UserAnswer;

use AppBundle\Entity\Quiz;
use AppBundle\Entity\UserAnswer;
use AppBundle\Entity\UserInterface;

interface UserAnswerManagerInterface
{
    public function createUserAnswer(UserInterface $user, Quiz $quiz, int $questionNumber, bool $isCorrect, bool $isFlush = false): UserAnswer;
}

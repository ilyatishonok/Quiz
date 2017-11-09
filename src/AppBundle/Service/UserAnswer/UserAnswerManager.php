<?php

declare(strict_types=1);

namespace AppBundle\Service\UserAnswer;

use AppBundle\Entity\Quiz;
use AppBundle\Entity\UserAnswer;
use AppBundle\Entity\UserInterface;

class UserAnswerManager implements UserAnswerManagerInterface
{
    public function createUserAnswer(UserInterface $user, Quiz $quiz, int $questionNumber, bool $isCorrect): UserAnswer
    {
        $userAnswer = new UserAnswer();
        $userAnswer->setUser($user);
        $userAnswer->setQuiz($quiz);
        $userAnswer->setQuestionNumber($questionNumber);
        $userAnswer->setCorrect($isCorrect);

        return $userAnswer;
    }
}

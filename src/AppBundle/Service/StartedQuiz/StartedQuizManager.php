<?php

declare(strict_types=1);

namespace AppBundle\Service\StartedQuiz;

use AppBundle\Entity\Quiz;
use AppBundle\Entity\StartedQuiz;
use AppBundle\Entity\UserInterface;

class StartedQuizManager implements StartedQuizManagerInterface
{
    public function createStartedQuiz(UserInterface $user, Quiz $quiz): StartedQuiz
    {
        $startedQuiz = new StartedQuiz();
        $startedQuiz->setQuiz($quiz);
        $startedQuiz->setUser($user);
        $startedQuiz->setStartTime(new \DateTime());

        return $startedQuiz;
    }

    public function updateStartedQuiz(StartedQuiz $quiz, int $questionNumber, bool $isCorrect): StartedQuiz
    {
        $quiz->setLastQuestionNumber($questionNumber+1);

        if($isCorrect) {
            $quiz->addRightAnswer();
        }

        return $quiz;
    }
}

<?php

declare(strict_types=1);

namespace AppBundle\Service\WiredQuestion;

use AppBundle\Entity\Question;
use AppBundle\Entity\Quiz;
use AppBundle\Entity\WiredQuestion;
use Doctrine\Common\Collections\Collection;

interface WiredQuestionManagerInterface
{
    public function createWiredQuestion(Question $question, Quiz $quiz, int $number): WiredQuestion;

    public function createWiredQuestions(array $questionsId, Quiz $quiz): Collection;
}

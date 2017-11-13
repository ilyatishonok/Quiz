<?php

declare(strict_types=1);

namespace AppBundle\Service\Answer;

use AppBundle\Exceptions\AnswerException;
use Doctrine\Common\Collections\Collection;

class AnswerManager implements AnswerManagerInterface
{
    public function setCorrectAnswer(Collection $answers, string $selectedAnswerName): void
    {
        $isExist = false;

        foreach ($answers as $answer) {
            if ($answer->getName() === $selectedAnswerName) {
                $isExist = true;
                $answer->setIsCorrect(true);
            }

            if (!$answer->getName()) {
                throw new AnswerException("Incorrect answer data!");
            }
        }

        if (!$isExist) {
            throw new AnswerException("Answer was not selected!");
        }
    }

    public function getAnswersNames(Collection $answers): array
    {
        $answerNames = array();
        foreach ($answers as $answer){
            $answerNames[$answer->getName()] = $answer->isCorrect();
        }

        return $answerNames;
    }
}

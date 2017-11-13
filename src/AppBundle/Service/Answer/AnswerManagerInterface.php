<?php

declare(strict_types=1);

namespace AppBundle\Service\Answer;

use Doctrine\Common\Collections\Collection;

interface AnswerManagerInterface
{
    public function setCorrectAnswer(Collection $answers, string $selectedAnswerName): void;

    public function getAnswersNames(Collection $answers): array;
}

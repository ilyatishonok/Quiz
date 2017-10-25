<?php

declare(strict_types=1);

namespace AppBundle\Service\Question;

use AppBundle\Entity\Question;

interface QuestionManagerInterface
{
    public function createQuestion(string $questionName, array $answers): Question;
}
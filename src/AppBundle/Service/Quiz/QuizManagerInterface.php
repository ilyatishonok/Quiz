<?php

declare(strict_types=1);

namespace AppBundle\Service\Quiz;

use AppBundle\Entity\Quiz;

interface QuizManagerInterface
{
    public function createQuiz(string $name, int $questions): Quiz;
}

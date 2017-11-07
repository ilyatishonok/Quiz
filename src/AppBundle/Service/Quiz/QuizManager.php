<?php

declare(strict_types=1);

namespace AppBundle\Service\Quiz;

use AppBundle\Entity\Quiz;
use Doctrine\ORM\EntityManagerInterface;

class QuizManager implements QuizManagerInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createQuiz(string $name, int $questions): Quiz
    {
        $quiz = new Quiz();
        $quiz->setName($name);
        $quiz->setCountOfQuestions($questions);

        $this->entityManager->persist($quiz);

        return $quiz;
    }
}

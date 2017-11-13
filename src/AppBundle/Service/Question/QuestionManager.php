<?php

declare(strict_types=1);

namespace AppBundle\Service\Question;

use AppBundle\Entity\Question;
use Doctrine\ORM\EntityManagerInterface;

class QuestionManager implements QuestionManagerInterface
{
    private $entityManager;
    public function __construct(EntityManagerInterface $manager)
    {
        $this->entityManager = $manager;
    }

    public function createQuestion(string $questionName, array $answers): Question
    {
        $question = new Question();
        $question->setName($questionName);
        return $question;
    }
}

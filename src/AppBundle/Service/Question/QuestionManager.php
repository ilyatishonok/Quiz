<?php

declare(strict_types=1);

namespace AppBundle\Service\Question;

use AppBundle\Entity\Question;
use AppBundle\Exceptions\AnswerException;
use AppBundle\Exceptions\QuestionException;
use AppBundle\Repository\AnswerRepository;
use AppBundle\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Config\Tests\Util\Validator;

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

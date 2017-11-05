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
    private $validator;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->entityManager = $manager;
    }


    private function checkAnswers(array $answers): bool
    {
        $correctAnswer = false;

        foreach ($answers as $name=>$isCorrect){
            if ($name === "")
            {
                return false;
            }
            if ($isCorrect) {
                $correctAnswer = true;
            }
        }

        if(!$correctAnswer){
            return false;
        }
        return true;
    }

    public function createQuestion(string $questionName, array $answers): Question
    {
        $question = new Question();
        $question->setName($questionName);
        return $question;
    }
}

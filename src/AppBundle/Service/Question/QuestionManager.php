<?php

declare(strict_types=1);

namespace AppBundle\Service\Question;

use AppBundle\Entity\Question;
use AppBundle\Exceptions\AnswerException;
use AppBundle\Exceptions\QuestionException;
use AppBundle\Repository\AnswerRepository;
use AppBundle\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;

class QuestionManager implements QuestionManagerInterface
{
    private $entityManager;

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
        if($questionName === "")
        {
            throw new QuestionException("Incorrect question name!");
        }
        if(!$this->checkAnswers($answers)){
            throw new AnswerException("Incorrect answers for question!");
        }

        /** @var QuestionRepository $questionRepository */
        $questionRepository = $this->entityManager->getRepository("AppBundle\Entity\Question");

        if($questionRepository->findBy(array("name"=>$questionName))){
            throw new QuestionException("This question is already exist!");
        }

        /** @var AnswerRepository $answerRepository */
        $answerRepository = $this->entityManager->getRepository("AppBundle\Entity\Answer");

        $question = $questionRepository->createQuestion($questionName);

        $answerRepository->createAnswers($answers, $question);

        $this->entityManager->flush();

        return $question;
    }
}

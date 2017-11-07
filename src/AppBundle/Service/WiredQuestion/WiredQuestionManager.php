<?php

declare(strict_types=1);

namespace AppBundle\Service\WiredQuestion;

use AppBundle\Entity\Question;
use AppBundle\Entity\Quiz;
use AppBundle\Entity\WiredQuestion;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Proxy\Proxy;
use Doctrine\ORM\EntityManagerInterface;

class WiredQuestionManager implements WiredQuestionManagerInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createWiredQuestions(array $questionsId, Quiz $quiz): Collection
    {
        $collection = new ArrayCollection();

        foreach ($questionsId as $key=>$id){
            /** @var Proxy $question */
            $question = $this->entityManager->getReference("AppBundle\Entity\Question", $id);

            $wiredQuestion = new WiredQuestion();
            $wiredQuestion->setQuestionProxy($question);
            $wiredQuestion->setQuestionNumber($key);
            $wiredQuestion->setQuiz($quiz);

            $collection->add($wiredQuestion);
            $this->entityManager->persist($wiredQuestion);
        }

        return $collection;
    }

    public function createWiredQuestion(Question $question, Quiz $quiz, int $number): WiredQuestion
    {
        $wiredQuestion = new WiredQuestion();
        $wiredQuestion->setQuestion($question);
        $wiredQuestion->setQuiz($quiz);
        $wiredQuestion->setQuestionNumber($number);

        $this->entityManager->persist($wiredQuestion);

        return $wiredQuestion;
    }
}

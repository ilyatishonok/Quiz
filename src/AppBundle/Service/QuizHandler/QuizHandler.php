<?php

declare(strict_types=1);

namespace AppBundle\Service\QuizHandler;

use AppBundle\Entity\Answer;
use AppBundle\Entity\CompletedQuiz;
use AppBundle\Entity\Quiz;
use AppBundle\Entity\StartedQuiz;
use AppBundle\Entity\UserAnswer;
use AppBundle\Entity\UserInterface;
use AppBundle\Entity\WiredQuestion;
use Doctrine\ORM\EntityManagerInterface;

class QuizHandler
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function updateQuizState(StartedQuiz $startedQuiz, int $questionNumber, bool $isCorrect): StartedQuiz
    {
        $startedQuiz->setLastQuestionNumber($questionNumber+1);

        if($isCorrect){
            $startedQuiz->addRightAnswer();
        }

        $this->entityManager->persist($startedQuiz);

        return $startedQuiz;
    }

    public function removeStartedQuiz(StartedQuiz $quiz)
    {
        $this->entityManager->remove($quiz);
    }

    public function closeQuiz(UserInterface $user, StartedQuiz $quiz){
        $completedQuiz = new CompletedQuiz();
        $completedQuiz->setQuiz($quiz->getQuiz());
        $completedQuiz->setRightQuestions($quiz->getRightAnswers());
        $completedQuiz->setUser($user);

        $startTime = $quiz->getStartTime();
        $time = $startTime->diff(new \DateTime());
        $completedQuiz->setTime($time);

        $this->entityManager->persist($completedQuiz);

        return $completedQuiz;
    }

    public function proccessSubmitAnswer(UserInterface $user, StartedQuiz $quiz, WiredQuestion $question, bool $isCorrect):void
    {
        $this->createUserAnswer($user,$quiz->getQuiz(),$question->getQuestionNumber(),$isCorrect);

        if(!($quiz->getQuiz()->getCountOfQuestions()-1 == $question->getQuestionNumber())){
           $this->updateQuizState($quiz,$question->getQuestionNumber(),$isCorrect);
           $this->entityManager->flush();
           return;
        }

        $this->entityManager->remove($quiz);
        $this->closeQuiz($user,$quiz);
        $this->entityManager->flush();
    }

    public function createUserAnswer(UserInterface $user, Quiz $quiz, int $questionNumber, bool $isCorrect): UserAnswer
    {
        $userAnswer = new UserAnswer();
        $userAnswer->setUser($user);
        $userAnswer->setQuiz($quiz);
        $userAnswer->setQuestionNumber($questionNumber);
        $userAnswer->setCorrect($isCorrect);

        $this->entityManager->persist($userAnswer);

        return $userAnswer;
    }

}
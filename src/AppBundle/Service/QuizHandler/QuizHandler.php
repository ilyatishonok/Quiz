<?php

declare(strict_types=1);

namespace AppBundle\Service\QuizHandler;

use AppBundle\Entity\CompletedQuiz;
use AppBundle\Entity\Quiz;
use AppBundle\Entity\StartedQuiz;
use AppBundle\Entity\User;
use AppBundle\Entity\UserInterface;
use AppBundle\Entity\WiredQuestion;
use AppBundle\Repository\CompletedQuizRepository;
use AppBundle\Service\CompletedQuiz\CompletedQuizManagerInterface;
use AppBundle\Service\DateIntervalHandler\DateIntervalHandler;
use AppBundle\Service\StartedQuiz\StartedQuizManagerInterface;
use AppBundle\Service\UserAnswer\UserAnswerManagerInterface;
use Doctrine\ORM\EntityManagerInterface;

class QuizHandler implements QuizHandlerInterface
{
    private $entityManager;
    private $startedQuizManager;
    private $userAnswerManager;
    private $completedQuizManager;

    public function __construct(EntityManagerInterface $entityManager,
                                StartedQuizManagerInterface $startedQuizManager,
                                UserAnswerManagerInterface $userAnswerManager,
                                CompletedQuizManagerInterface $completedQuizManager
    )
    {
        $this->entityManager = $entityManager;
        $this->startedQuizManager = $startedQuizManager;
        $this->userAnswerManager = $userAnswerManager;
        $this->completedQuizManager = $completedQuizManager;
    }

    public function handleQuiz()
    {

    }


    protected function synchronizeLeader(Quiz $quiz, CompletedQuiz $completedQuiz): void
    {
        $leader = $quiz->getLeader();

        if(!$leader)
        {
            $quiz->setLeader($completedQuiz->getUser()->getUsername());
            $this->entityManager->persist($quiz);
            return;
        }

        /** @var CompletedQuizRepository $completedQuizRepository */
        $completedQuizRepository = $this->entityManager->getRepository(CompletedQuiz::class);

        $completedQuizLeader = $completedQuizRepository->loadLeader($quiz);

        if($completedQuiz->getRightQuestions() > $completedQuizLeader->getRightQuestions())
        {
            $quiz->setLeader($completedQuiz->getUser()->getUsername());
        } else if($completedQuiz->getRightQuestions() === $completedQuiz->getRightQuestions())
        {
            if($completedQuiz->getStartTime()){
                $quiz->setLeader($completedQuiz->getUser()->getUsername());
            }
        }

    }

    public function proccessSubmitAnswer(UserInterface $user, StartedQuiz $startedQuiz, WiredQuestion $question, bool $isCorrect): void
    {
        $userAnswer = $this->userAnswerManager->createUserAnswer($user,$startedQuiz->getQuiz(),$question->getQuestionNumber(),$isCorrect);

        $this->entityManager->persist($userAnswer);

        if(!($startedQuiz->getQuiz()->getCountOfQuestions()-1 == $question->getQuestionNumber())){
           $this->startedQuizManager->updateStartedQuiz($startedQuiz,$question->getQuestionNumber(),$isCorrect);

           $this->entityManager->flush();
           return;
        }

        $completedQuiz = $this->completedQuizManager->createCompletedQuiz($user,$startedQuiz,$startedQuiz->getRightAnswers()+(int)$isCorrect);

        $this->synchronizeLeader($startedQuiz->getQuiz(),$completedQuiz);

        $startedQuiz->getQuiz()->removePlayer();

        $this->entityManager->persist($completedQuiz);
        $this->entityManager->remove($startedQuiz);
        $this->entityManager->flush();
    }


}
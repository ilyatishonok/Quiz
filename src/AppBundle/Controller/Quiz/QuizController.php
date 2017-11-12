<?php

declare(strict_types=1);

namespace AppBundle\Controller\Quiz;

use AppBundle\Entity\CompletedQuiz;
use AppBundle\Entity\Quiz;
use AppBundle\Entity\StartedQuiz;
use AppBundle\Entity\WiredQuestion;
use AppBundle\Form\StartQuizType;
use AppBundle\Choices\AnswerChoice;
use AppBundle\Form\QuestionChoiceType;
use AppBundle\Service\StartedQuiz\StartedQuizManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class QuizController extends Controller
{
    private $startedQuizManager;
    private $completedQuizRepository;
    private $startedQuizRepository;
    private $wiredQuestionRepository;

    public function __construct(StartedQuizManagerInterface $startedQuizManager, EntityManagerInterface $entityManager)
    {
        $this->startedQuizManager = $startedQuizManager;
        $this->completedQuizRepository = $entityManager->getRepository(CompletedQuiz::class);
        $this->startedQuizRepository = $entityManager->getRepository(StartedQuiz::class);
        $this->wiredQuestionRepository = $entityManager->getRepository(WiredQuestion::class);
    }

    /**
     * @Route("/quiz/{id}", name="_quiz")
     */
    public function showQuizAction(Request $request, string $id): Response
    {
        $quiz = $this->getDoctrine()->getManager()->find(Quiz::class,(integer)$id);

        if (!$quiz) {
            throw $this->createNotFoundException("The quiz doesn't exist!");
        }

        if (!$quiz->isEnabled()) {
            return $this->render("quiz/deactivate.html.twig");
        }

        $startedQuiz = $this->startedQuizRepository->findOneBy(array("user" => $this->getUser(), "quiz" => $quiz));

        if ($startedQuiz) {
            $this->redirectToRoute("_quiz_passing", array("id" => $id));
        }

        $completedQuiz = $this->completedQuizRepository->findOneBy(array("user" => $this->getUser(), "quiz" => $quiz));

        if ($completedQuiz)
        {
            $completedQuizzes = $this->completedQuizRepository->loadLeaders($quiz);
            $position = $this->completedQuizRepository->loadUserPosition($completedQuiz->getRightQuestions(), $completedQuiz->getSeconds());
            return $this->render("quiz/leader_board.html.twig", array('leaders' => $completedQuizzes, "position" => $position));
        }

        return $this->redirectToRoute("_quiz_start", array("id" => $id));
    }

    /**
     * @Route("/quiz/start-quiz/{id}", name="_quiz_start")
     */
    public function startQuizAction(Request $request, string $id): Response
    {
        $quiz = $this->getDoctrine()->getManager()->find(Quiz::class, (integer)$id);

        if (!$quiz) {
            throw $this->createNotFoundException("The quiz doesn't exist!");
        }

        if (!$quiz->isEnabled()) {
            return $this->render("quiz/deactivated.html.twig");
        }

        $completedQuiz = $this->completedQuizRepository->findOneBy(array("user" => $this->getUser(), "quiz" => $quiz));

        if ($completedQuiz) {
            return $this->redirectToRoute("_quiz", array("id" => $id));
        }

        $startedQuiz = $this->startedQuizRepository->findOneBy(array("user" => $this->getUser(), "quiz" => $quiz));

        if ($startedQuiz) {
            return $this->redirectToRoute("_quiz_passing", array("id" => $id));
        }

        $form = $this->createForm(StartQuizType::class,null, array(
            "action" => $this->generateUrl("_quiz_start", array('id' => $id)),
        ));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $startedQuiz = $this->startedQuizManager->createStartedQuiz($this->getUser(), $quiz);
            $quiz->addPlayer();

            $this->getDoctrine()->getManager()->persist($startedQuiz);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute("_quiz", array("id" => $quiz->getId()));
        }

        return $this->render("quiz/quiz_start.html.twig", array("quiz" => $quiz, "form" => $form->createView()));
    }

    /**
     * @Route("/quiz/passing/{id}", name="_quiz_passing")
     */
    public function passingQuizAction(Request $request, string $id): Response
    {
        $quiz = $this->getDoctrine()->getManager()->find(Quiz::class, $id);

        if (!$quiz) {
            throw $this->createNotFoundException("The quiz doesn't exist!");
        }

        if (!$quiz->isEnabled()) {
            return $this->render("quiz/deactivate.html.twig");
        }

        $startedQuiz = $this->startedQuizRepository->findOneBy(array("user" => $this->getUser(), "quiz" => $quiz));

        if(!$startedQuiz)
        {
            $completedQuiz = $this->completedQuizRepository->findOneBy(array("user" => $this->getUser(), "quiz" => $quiz));

            if (!$completedQuiz) {
                return $this->redirectToRoute("_quiz_start", array("id" => $id));
            }
            $completedQuizzes = $this->completedQuizRepository->loadLeaders($quiz);
            $position = $this->completedQuizRepository->loadUserPosition($completedQuiz->getRightQuestions(), $completedQuiz->getSeconds());

            return $this->render("quiz/leader_board.html.twig", array('leaders' => $completedQuizzes, "position" => $position));
        }

        $wiredQuestion = $this->wiredQuestionRepository->loadByQuizAndNumber($quiz, $startedQuiz->getLastQuestionNumber());

        $question = $wiredQuestion->getQuestion();

        $choice = new AnswerChoice();
        $form = $this->createForm(QuestionChoiceType::class,$choice,array(
            "answers"=>$question->getAnswers(),
            "action"=>$this->generateUrl("_quiz_passing", array('id' => $id)),
        ));

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $quizHandler = $this->get("quiz_handler");
            $quizHandler->handleProcessQuiz($this->getUser(), $startedQuiz, $wiredQuestion, $choice->getAnswer()->isCorrect());

            return $this->render("quiz/answered_question.html.twig",array(
                'id' => $quiz->getId(),
                "question_name" => $question->getName(),
                "answers" => $question->getAnswers(),
                "user_choice" => $choice->getAnswer()
            ));
        }

        return $this->render("quiz/started_quiz.html.twig",array(
            'answers' => $question->getAnswers(),
            "question_name" => $question->getName(),
            "questions" =>$quiz->getCountOfQuestions(),
            "questionNumber" => $wiredQuestion->getQuestionNumber(),
            "form" => $form->createView()));
    }
}

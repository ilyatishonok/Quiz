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
use AppBundle\Repository\WiredQuestionRepository;
use AppBundle\Service\StartedQuiz\StartedQuizManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class QuizController extends Controller
{
    private $startedQuizManager;

    public function __construct(StartedQuizManagerInterface $startedQuizManager)
    {
        $this->startedQuizManager = $startedQuizManager;
    }

    /**
     * @Route("/quiz/{id}", name="_quiz")
     */
    public function showQuizAction(Request $request,string $id)
    {
        $quizRepository = $this->getDoctrine()->getManager()->getRepository(Quiz::class);
        $quiz = $quizRepository->find((integer)$id);

        if(!$quiz){
            return $this->render("quiz/quizNotFound.html.twig");
        }

        if(!$quiz->isEnabled()){
            return $this->render("quiz/deactivate.html.twig");
        }

        $startedQuizRepository = $this->getDoctrine()->getManager()->getRepository(StartedQuiz::class);
        $startedQuiz = $startedQuizRepository->findOneBy(array("user"=>$this->getUser(), "quiz"=>$quiz));

        if($startedQuiz)
        {
            return $this->userSubmitAnswerAction($request,$startedQuiz);
        }

        $completedQuizRepository = $this->getDoctrine()->getManager()->getRepository(CompletedQuiz::class);
        $completedQuiz = $completedQuizRepository->findOneBy(array("user"=>$this->getUser(), "quiz"=>$quiz));

        if($completedQuiz)
        {
            $completedQuizes = $completedQuizRepository->loadLeaders($quiz);
            return $this->render("quiz/rating.html.twig", array('leaders'=>$completedQuizes));
        }

        return $this->startQuizAction($request,$quiz);
    }

    private function startQuizAction(Request $request,Quiz $quiz): Response
    {
        $form = $this->createForm(StartQuizType::class);

        $form->handleRequest($request);

        if($form->isSubmitted())
        {
            $startedQuiz = $this->startedQuizManager->createStartedQuiz($this->getUser(), $quiz);
            $quiz->addPlayer();

            $this->getDoctrine()->getManager()->persist($startedQuiz);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute("_quiz",array("id"=>$quiz->getId()));
        }

        return $this->render("quiz/quiz_start.html.twig", array("quiz"=>$quiz,"form"=>$form->createView()));
    }

    private function userSubmitAnswerAction(Request $request, StartedQuiz $startedQuiz): Response
    {
        $quiz = $startedQuiz->getQuiz();

        $wiredQuestionRepository = $this->getDoctrine()->getManager()->getRepository(WiredQuestion::class);
        $wiredQuestion = $wiredQuestionRepository->loadByQuizAndNumber($quiz,$startedQuiz->getLastQuestionNumber());

        $question = $wiredQuestion->getQuestion();

        $choice = new AnswerChoice();
        $form = $this->createForm(QuestionChoiceType::class,$choice,array(
            "answers"=>$question->getAnswers(),
            "action"=>$this->generateUrl("_quiz",array('id'=>$quiz->getId())),
        ));

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $quizHandler = $this->get("quiz_handler");
            $quizHandler->proccessSubmitAnswer($this->getUser(),$startedQuiz,$wiredQuestion,$choice->getAnswer()->isCorrect());
            return $this->render("quiz/answered_question.html.twig",array(
                'id'=>$quiz->getId(),
                "question_name"=>$question->getName(),
                "answers"=>$question->getAnswers(),
                "user_choice"=>$choice->getAnswer()
            ));
        }

        return $this->render("quiz/started.html.twig",array('answers'=>$question->getAnswers(),"question_name"=>$question->getName(),"form"=>$form->createView()));
    }
}

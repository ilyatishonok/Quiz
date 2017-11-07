<?php

declare(strict_types=1);

namespace AppBundle\Controller\Quiz;

use AppBundle\Entity\StartedQuiz;
use AppBundle\Form\StartQuizType;
use AppBundle\Choices\AnswerChoice;
use AppBundle\Form\QuestionChoiceType;
use AppBundle\Repository\WiredQuestionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class QuizController extends Controller
{
    /**
     * @Route("/quiz/{id}", name="_quiz")
     */
    public function showQuizAction(Request $request,string $id)
    {
        $quizRepository = $this->getDoctrine()->getManager()->getRepository("AppBundle\Entity\Quiz");
        $quiz = $quizRepository->find((integer)$id);

        if(!$quiz){
            return $this->render("quiz/quizNotFound.html.twig");
        }

        if(!$quiz->isEnable()){
            return $this->render("quiz/deactivate.html.twig");
        }

        $startedQuizRepository = $this->getDoctrine()->getManager()->getRepository("AppBundle\Entity\StartedQuiz");
        $startedQuiz = $startedQuizRepository->findOneBy(array("user"=>$this->getUser(), "quiz"=>$quiz));

        if($startedQuiz)
        {
            $data = array("quiz"=>$quiz,"startedQuiz"=>$startedQuiz,"id"=>$id);
            return $this->proccessUserSubmitAction($request,$data);
        }

        $completedQuizRepository = $this->getDoctrine()->getManager()->getRepository("AppBundle\Entity\CompletedQuiz");
        $completedQuiz = $completedQuizRepository->findOneBy(array("user"=>$this->getUser(), "quiz"=>$quiz));

        if($completedQuiz)
        {
            $completedQuizes = $completedQuizRepository->loadLeaders($quiz);
            return $this->render("quiz/rating.html.twig", array('leaders'=>$completedQuizes));
        }


        $form = $this->createForm(StartQuizType::class);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $startedQuiz = new StartedQuiz();
            $startedQuiz->setUser($this->getUser());
            $startedQuiz->setStartTime(new \DateTime());
            $startedQuiz->setQuiz($quiz);
            $startedQuiz->setLastQuestionNumber(0);

            $this->getDoctrine()->getManager()->persist($startedQuiz);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute("_quiz",array("id"=>$id));
        }
        return $this->render("quiz/quiz_start.html.twig", array("quiz"=>$quiz,"form"=>$form->createView()));

    }


    private function proccessUserSubmitAction(Request $request, array $data): Response
    {
        $quiz = $data['quiz'];
        $startedQuiz = $data['startedQuiz'];
        $id = $data['id'];

        $wiredQuestionRepository = $this->getDoctrine()->getManager()->getRepository("AppBundle\Entity\WiredQuestion");
        $wiredQuestion = $wiredQuestionRepository->findOneBy(array("quiz"=>$quiz, "questionNumber"=>$startedQuiz->getLastQuestionNumber()));

        $choice = new AnswerChoice();
        $form = $this->createForm(QuestionChoiceType::class,$choice,array("question"=>$wiredQuestion->getQuestion(),"action"=>$this->generateUrl("_quiz",array('id'=>$id)),"method"=> "GET"));

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $quizHandler = $this->get("quiz_handler");
            $quizHandler->proccessSubmitAnswer($this->getUser(),$startedQuiz,$wiredQuestion,$choice->getAnswer()->isCorrect());
            return $this->render("quiz/answered_question.html.twig",array('id'=>$quiz->getId(),"question_name"=>$wiredQuestion->getQuestion()->getName(),"answers"=>$wiredQuestion->getQuestion()->getAnswers()));
        }

        $userAnswerRepository = $this->getDoctrine()->getManager()->getRepository("AppBundle\Entity\UserAnswer");
        $userAnswers = $userAnswerRepository->findBy(array("quiz"=>$quiz,"user"=>$this->getUser()));

        return $this->render("quiz/started.html.twig",array("answers"=>$userAnswers,'',"question_name"=>$wiredQuestion->getQuestion()->getName(),"form"=>$form->createView()));
    }

    /**
     * @Route("/start_quiz")
     */
    public function testStartQuiz(Request $request)
    {
        $form = $this->createForm(StartQuizType::class);
        return $this->render("quiz/quiz_start.html.twig", array('form' => $form->createView()));
    }
}

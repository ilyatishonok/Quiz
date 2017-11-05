<?php

declare(strict_types=1);

namespace AppBundle\Controller\Quiz;

use AppBundle\Entity\StartedQuiz;
use AppBundle\Form\AnswerChoice;
use AppBundle\Form\QuestionChoiceType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

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

            return $this->render("quiz/started.html.twig",array("question_name"=>$wiredQuestion->getQuestion()->getName(),"form"=>$form->createView()));
        }

        $completedQuizRepository = $this->getDoctrine()->getManager()->getRepository("AppBundle\Entity\CompletedQuiz");
        $completedQuiz = $completedQuizRepository->findOneBy(array("user"=>$this->getUser(), "quiz"=>$quiz));

        if($completedQuiz)
        {

            return $this->render("quiz/leader_board.html.twig");
        }

    }
}

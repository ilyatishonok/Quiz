<?php

namespace AppBundle\Controller\Quiz;

use AppBundle\Entity\StartedQuiz;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class QuizController extends Controller
{
    /**
     * @Route("/quiz/{id}")
     */
    public function showQuizAction(string $id)
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

        if($startedQuiz){
            return $this->render("quiz/started.html.twig", array("question"=>$startedQuiz->getLastQuestion()));
        }

        $completedQuizRepository = $this->getDoctrine()->getManager()->getRepository("AppBundle\Entity\CompletedQuiz");
        $completedQuiz = $completedQuizRepository->findOneBy(array("user"=>$this->getUser(), "quiz"=>$quiz));

        if($completedQuiz){

        }

    }

}

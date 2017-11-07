<?php

declare(strict_types=1);

namespace AppBundle\Controller\Home;

use AppBundle\Entity\WiredQuestion;
use AppBundle\Exceptions\QuestionException;
use AppBundle\Repository\CompletedQuizRepository;
use AppBundle\Repository\QuestionRepository;
use AppBundle\Repository\WiredQuestionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function showHomePageAction(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $dql = "SELECT q FROM AppBundle\Entity\Quiz q";
        $query = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            9
        );

        return $this->render('home/homepage.html.twig', array('pagination' => $pagination));
    }

    /**
     * @Route("/started-quizes", name="started_quizes")
     */
    public function showStartedQuizesAction(Request $request)
    {
        $quizRepository = $this->getDoctrine()->getManager()->getRepository("AppBundle\Entity\Quiz");
        $quiz = $quizRepository->findOneBy(array("id"=>10));
        /** @var CompletedQuizRepository $completedQuizRep */
        $completedQuizRep = $this->getDoctrine()->getManager()->getRepository("AppBundle\Entity\CompletedQuiz");
        $compleQuiz = $completedQuizRep->findOneBy(array("user"=>$this->getUser(), "quiz"=>$quiz));
        /** @var \DateInterval $time */
        $time = $compleQuiz->getTime();
        //$pos = $completedQuizRep->loadUserPosition($compleQuiz->getRightQuestions(),$compleQuiz->getTime()->);
        exit(dump($time));
        return $this->render("base.html.twig");
    }   
}

<?php

declare(strict_types=1);

namespace AppBundle\Controller\Home;

use AppBundle\Entity\CompletedQuiz;
use AppBundle\Entity\Question;
use AppBundle\Entity\Quiz;
use AppBundle\Entity\StartedQuiz;
use AppBundle\Entity\WiredQuestion;
use AppBundle\Exceptions\QuestionException;
use AppBundle\Repository\CompletedQuizRepository;
use AppBundle\Repository\QuestionRepository;
use AppBundle\Repository\StartedQuizRepository;
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
            5
        );

        return $this->render('home/homepage.html.twig', array('pagination' => $pagination));
    }

    /**
     * @Route("/started-quizes", name="started_quizes")
     */
    public function showStartedQuizesAction(Request $request): Response
    {
        /** @var StartedQuizRepository $startedQuizeRepository */
        $startedQuizRepository = $this->getDoctrine()->getManager()->getRepository(StartedQuiz::class);

        $query = $startedQuizRepository->createLoaderQuery($this->getUser());

        $paginator = $this->get("knp_paginator");

        $startedQuizes = $paginator->paginate(
            $query,
            $request->query->getInt('page',1),
            5
        );

        return $this->render("home/started_quizes.html.twig", array("started_quizes"=>$startedQuizes));
    }

    /**
     * @Route("/completed-quizes", name="completed_quizes")
     */
    public function showCompletedQuizesAction(Request $request): Response
    {
        $completedQuizRepository = $this->getDoctrine()->getManager()->getRepository(CompletedQuiz::class);

        $query = $completedQuizRepository->createLoaderQuery($this->getUser());

        $paginator = $this->get("knp_paginator");

        $completedQuizes = $paginator->paginate(
            $query,
            $request->query->getInt('page',1),
            5
        );

        return $this->render("home/completed_quizes.html.twig", array("completed_quizes"=>$completedQuizes));
    }

}

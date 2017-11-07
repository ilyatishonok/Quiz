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
        /** @var WiredQuestionRepository $em */
        $em = $this->getDoctrine()->getManager()->getRepository("AppBundle\Entity\WiredQuestion");
        try {
            $em->abraKadaBra();
        }catch (QuestionException $exception){
            return new Response($exception->getMessage());
        }
       return $this->render("base.html.twig");
    }

    /**
     * @Route("/test")
     */
    public function test(Request $request)
    {
        $em = $this->getDoctrine();
        return $this->render("base.html.twig");
    }
}

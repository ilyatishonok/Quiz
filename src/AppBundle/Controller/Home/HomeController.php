<?php

namespace AppBundle\Controller\Home;

use AppBundle\Entity\Question;
use AppBundle\Entity\Quiz;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function showHomePageAction(Request $request)
    {
        $em    = $this->get('doctrine.orm.entity_manager');

        $dql   = "SELECT q FROM AppBundle\Entity\Quiz q ";

        $query = $em->createQuery($dql);

        $paginator  = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            9
        );

        return $this->render('default/index.html.twig', array('pagination' => $pagination));
    }
}

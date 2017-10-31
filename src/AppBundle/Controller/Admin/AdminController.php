<?php

namespace AppBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\User;
use AppBundle\Entity\Quiz;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends Controller
{
    /**
     * @Route("/admin/main")
     */
    public function showAdminPageAction()
    {
    }

    /**
     * @Route("admin/create/quiz", name="quiz_creation")
     */
    public function showQuizCreationAction()
    {
        return $this->render("admin/quiz.html.twig");
    }

    /**
     * @Route("/admin")
     */
    public function showAdminPanel()
    {
        return $this->render("admin/panel.html.twig");
    }

    /**
     * @Route("admin/create/question", name="question_creation")
     */
    public function showQuestionCreationAction()
    {
        return $this->render("admin/question.html.twig");
    }

    /**
     * @Route("admin/edit/quiz")
     */
    public function showQuizEditingAction(){

    }

    /**
     * @Route("/admin/user", name="user_manager")
     */
    public function showAdminUserManager(Request $request)
    {
        $em    = $this->get('doctrine.orm.entity_manager');

        $dql   = "SELECT q FROM AppBundle\Entity\User q ";

        $query = $em->createQuery($dql);

        $paginator  = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('admin/user.html.twig', array('pagination' => $pagination));
    }


    /**
     *@Route("/admin/quiz-manager", name="quiz_manager")
     */
    public function showQuizManagerPanel(Request $request)
    {
        $em    = $this->get('doctrine.orm.entity_manager');
        $dql   = "SELECT q FROM AppBundle\Entity\Quiz q ";
        $query = $em->createQuery($dql);
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            13
        );
        return $this->render("admin/quiz-manager.html.twig", array('pagination' => $pagination));
    }
}

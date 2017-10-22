<?php

namespace AppBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class AdminController extends Controller
{
    /**
     * @Route("/admin/main")
     */
    public function showAdminPageAction()
    {
    }

    /**
     * @Route("admin/create/quiz")
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
     * @Route("admin/create/question")
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


}

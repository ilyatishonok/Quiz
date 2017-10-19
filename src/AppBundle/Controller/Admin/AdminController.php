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

    }

    /**
     * @Route("admin/create/question")
     */
    public function showQuestingCreationAction()
    {
    }

    /**
     * @Route("admin/edit/quiz")
     */
    public function showQuizEditingAction(){

    }


}

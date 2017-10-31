<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Question;
use AppBundle\Form\QuestionType;
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
     * @Route("admin/create/quiz", name="quiz_creation")
     */
    public function showQuizCreationAction()
    {
        return $this->render("admin/quiz.html.twig");
    }


    /**
     * @Route("admin/user-manager", name="user_manager")
     */
    public function showUserManagerAction(){
        return $this->render("admin/user_manager.html.twig");
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
        $question = new Question();

        $form = $this->createForm(QuestionType::class, $question);

        return $this->render("admin/question.html.twig", array("form"=>$form->createView()));
    }

    /**
     * @Route("admin/edit/quiz")
     */
    public function showQuizEditingAction(){

    }


}

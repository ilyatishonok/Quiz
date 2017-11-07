<?php

declare(strict_types=1);

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Question;
use AppBundle\Form\QuestionType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends Controller
{
    /**
     * @Route("admin/create/quiz", name="quiz_creation")
     */
    public function showQuizCreationAction()
    {
        $question = new Question();

        $form = $this->createForm(QuestionType::class, $question);
        return $this->render("admin/quiz.html.twig", array("form"=>$form->createView(   )));
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

        return $this->render("admin/new_question.html.twig", array("form"=>$form->createView()));
    }

    /**
     * @Route("admin/edit/quiz")
     */
    public function showQuizEditingAction(){

    }

    /**
     * @Route("/admin/user-manager", name="user_manager")
     */
    public function showUserManagerAction(Request $request)
    {
        $search = $request->get("search");

        $entityManager = $this->getDoctrine()->getManager();

        if($search) {
            $query = $entityManager->createQuery("SELECT u FROM AppBundle\Entity\User u WHERE u.username LIKE :username OR u.email LIKE :username");
            $query->setParameter("username",$search."%");
        } else {
            $query  = $entityManager->createQuery("SELECT u FROM AppBundle\Entity\User u ");
        }

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            7
        );

        return $this->render('admin/user_manager.html.twig', array('pagination' => $pagination));
    }


    /**
     *@Route("/admin/quiz-manager", name="quiz_manager")
     */
    public function showQuizManagerPanelAction(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $dql = "SELECT quiz FROM AppBundle\Entity\Quiz quiz ";
        $query = $em->createQuery($dql);
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            7
        );

        return $this->render("admin/quiz_manager.html.twig", array('pagination' => $pagination));
    }

}

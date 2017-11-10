<?php

declare(strict_types=1);

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Question;
use AppBundle\Entity\User;
use AppBundle\Form\QuestionType;
use AppBundle\Repository\UserRepository;
use AppBundle\Service\Grid\GridLoader;
use AppBundle\Service\Grid\GridLoaderInterface;
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
        /** @var GridLoader $gridLoader */
        $gridLoader = $this->get("grid_loader");


        $userGrid = $this->getParameter("user_grid");

        $table = $gridLoader->loadGrid(
            array(
                 "className"=>User::class,
                 "limit"=>5,
                 "entityName"=>"user",
                 "request"=>$request,
                 "translationDomain"=>$userGrid['translation_domain'],
                 "tableFields"=>$userGrid['table_fields'],
                 "sortableFields"=>$userGrid['sortable_fields'],
                 "filterableFields"=>$userGrid['filterable_fields'],
            )
        );

        return $this->render('admin/user_manager.html.twig', array("table"=>$table));
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
            6
        );

        return $this->render("admin/quiz_manager.html.twig", array('pagination' => $pagination));
    }

}

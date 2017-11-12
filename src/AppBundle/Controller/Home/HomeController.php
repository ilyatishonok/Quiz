<?php

declare(strict_types=1);

namespace AppBundle\Controller\Home;

use AppBundle\Entity\CompletedQuiz;
use AppBundle\Entity\Quiz;
use AppBundle\Entity\StartedQuiz;
use AppBundle\Repository\CompletedQuizRepository;
use AppBundle\Repository\QuizRepository;
use AppBundle\Repository\StartedQuizRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends Controller
{
    protected $paginator;

    public function __construct(PaginatorInterface $paginator)
    {
        $this->paginator = $paginator;
    }

    /**
     * @Route("/", name="_homepage")
     */
    public function showHomePageAction(Request $request): Response
    {
        /** @var QuizRepository $quizRepository */
        $quizRepository = $this->getDoctrine()->getManager()->getRepository(Quiz::class);

        $form = $this->createForm(SearchType::class,null, array(
            "method" => "GET",
            "action" => $this->generateUrl("_homepage"),
            "required" => false,
            "empty_data" => "",
        ));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $searchQuery = $form->getData();
            $query = $quizRepository->createLoaderQueryByName($searchQuery);
        } else {
            $query = $quizRepository->createLoaderQuery();
        }

        $quizzes = $this->paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            6
        );

        return $this->render('home/homepage.html.twig', array('quizzes' => $quizzes, "form" => $form->createView()));
    }

    /**
     * @Route("/started-quizzes", name="_started_quizzes")
     */
    public function showStartedQuizzesAction(Request $request): Response
    {
        /** @var StartedQuizRepository $startedQuizRepository */
        $startedQuizRepository = $this->getDoctrine()->getManager()->getRepository(StartedQuiz::class);

        $form = $this->createForm(SearchType::class, null, array(
            "method" => "GET",
            "action" => $this->generateUrl("_started_quizzes"),
            "required" => false,
            "empty_data" => "",
        ));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $searchQuery = $form->getData();
            $query = $startedQuizRepository->createLoaderQueryByUserAndName($this->getUser(),$searchQuery);
        } else {
            $query = $startedQuizRepository->createLoaderQueryByUser($this->getUser());
        }

        $startedQuizzes = $this->paginator->paginate(
            $query,
            $request->query->getInt('page',1),
            6
        );

        return $this->render("home/started_quizzes.html.twig", array("started_quizzes" => $startedQuizzes, "form" => $form->createView()));
    }

    /**
     * @Route("/completed-quizzes", name="_completed_quizzes")
     */
    public function showCompletedQuizzesAction(Request $request): Response
    {
        /** @var CompletedQuizRepository $completedQuizRepository */
        $completedQuizRepository = $this->getDoctrine()->getManager()->getRepository(CompletedQuiz::class);

        $form = $this->createForm(SearchType::class, null , array(
            "method" => "GET",
            "action" => $this->generateUrl("_completed_quizzes"),
            "required" => false,
            "empty_data" => "",
        ));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $searchQuery = $form->getData();
            $query = $completedQuizRepository->createLoaderQueryByUserAndName($this->getUser(),$searchQuery);
        } else {
            $query = $completedQuizRepository->createLoaderQueryByUser($this->getUser());

        }

        $completedQuizzes = $this->paginator->paginate(
            $query,
            $request->query->getInt('page',1),
            6
        );

        return $this->render("home/completed_quizzes.html.twig", array("completed_quizzes" => $completedQuizzes, "form" => $form->createView()));
    }
}

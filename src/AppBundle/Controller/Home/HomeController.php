<?php

declare(strict_types=1);

namespace AppBundle\Controller\Home;

use AppBundle\Entity\CompletedQuiz;
use AppBundle\Entity\Question;
use AppBundle\Entity\Quiz;
use AppBundle\Entity\StartedQuiz;
use AppBundle\Entity\User;
use AppBundle\Entity\WiredQuestion;
use AppBundle\Exceptions\QuestionException;
use AppBundle\Repository\CompletedQuizRepository;
use AppBundle\Repository\QuestionRepository;
use AppBundle\Repository\QuizRepository;
use AppBundle\Repository\StartedQuizRepository;
use AppBundle\Repository\WiredQuestionRepository;
use AppBundle\Service\Grid\GridLoader;
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
            "method"=>"GET",
            "action"=>$this->generateUrl("_homepage"),
            "required"=>false,
            "empty_data"=>"",
        ));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $searchQuery = $form->getData();
            $query = $quizRepository->createQueryByName($searchQuery);
        } else {
            $query = $quizRepository->createQuery();
        }

        $quizzes = $this->paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            6
        );

        return $this->render('home/homepage.html.twig', array('quizzes' => $quizzes, "form"=>$form->createView()));
    }

    /**
     * @Route("/started-quizes", name="started_quizes")
     */
    public function showStartedQuizesAction(Request $request): Response
    {
        /** @var StartedQuizRepository $startedQuizeRepository */
        $startedQuizRepository = $this->getDoctrine()->getManager()->getRepository(StartedQuiz::class);

        $query = $startedQuizRepository->createLoaderQuery($this->getUser());

        $startedQuizes = $this->paginator->paginate(
            $query,
            $request->query->getInt('page',1),
            12
        );

        return $this->render("home/started_quizes.html.twig", array("started_quizes"=>$startedQuizes));
    }

    /**
     * @Route("/started-quizess", name="starteds_quizes")
     */
    public function showStartedsQuizesAction(Request $request): Response
    {
        $quizesRepository = $this->getDoctrine()->getManager()->getRepository(Quiz::class);

        return $this->render("base.html.twig");
    }

    /**
     * @Route("/completed-quizes", name="completed_quizes")
     */
    public function showCompletedQuizesAction(Request $request): Response
    {

        $completedQuizRepository = $this->getDoctrine()->getManager()->getRepository(CompletedQuiz::class);

        $query = $completedQuizRepository->createLoaderQuery($this->getUser());

        $completedQuizes = $this->paginator->paginate(
            $query,
            $request->query->getInt('page',1),
            2
        );

        return $this->render("home/completed_quizes.html.twig", array("completed_quizes"=>$completedQuizes));
    }

}

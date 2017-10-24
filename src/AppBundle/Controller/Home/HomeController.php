<?php

namespace AppBundle\Controller\Home;

use AppBundle\Entity\Question;
use AppBundle\Entity\Quiz;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function showHomePageAction(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager()->getRepository("AppBundle\Entity\Quiz");
        /** @var Quiz $quiz */
        $quiz = $entityManager->find(9);
        $questions = $quiz->getQustions();
        $questionId = array();
        foreach ($questions as $question){
            $questionId[] = $question->getId();
        }
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'questions' => $questionId
        ]);
    }
}

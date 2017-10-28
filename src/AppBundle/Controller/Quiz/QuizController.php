<?php

namespace AppBundle\Controller\Quiz;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class QuizController extends Controller
{
    /**
     * @Route("/quiz/{id}")
     */
    public function showQuizAction(string $id)
    {
        $quizRepository = $this->getDoctrine()->getManager()->getRepository("AppBundle");
    }

}

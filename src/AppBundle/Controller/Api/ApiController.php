<?php

declare(strict_types=1);

namespace AppBundle\Controller\Api;

use AppBundle\Entity\Question;
use AppBundle\Repository\AnswerRepository;
use AppBundle\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\Tests\Compiler\J;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class ApiController extends Controller
{

    /**
     * @Route("/api/check-question/{id}")
     */
    public function checkQuestionAction(string $id) : Response
    {

    }


    /**
     * @Route("/admin/api/create/question");
     */
    public function createQuestionAction(Request $request)
    {
        $content = $request->getContent();
        if(!empty($content)){
            $data = json_decode($content,true);

            /** @var QuestionRepository $questionRepository */
            $questionRepository = $this->getDoctrine()->getManager()->getRepository("AppBundle\Entity\Question");
            /** @var AnswerRepository $answerRepository */
            $answerRepository = $this->getDoctrine()->getManager()->getRepository("AppBundle\Entity\Answer");

            $question = $questionRepository->createQuestion($data['questionName']);

            $answerRepository->saveAnswers($data['answers'], $question);
            return new JsonResponse("All work");
        }
    }

    /**
     * @Route("/api/questions");
     */
    public function getQuestionsAction(Request $request){
        $regular = $request->get("regular");
        $entityManager = $this->getDoctrine()->getManager();

        /** @var QuestionRepository $questionRepository */
        $questionRepository = $entityManager->getRepository("AppBundle\Entity\Question");

        $questions = $questionRepository->loadQuestionsByRegular($regular);
        $array = array();
        foreach ($questions as $question){
            $array[] = $question->getName();
        }

        return new JsonResponse($array);
    }
}
<?php

declare(strict_types=1);

namespace AppBundle\Controller\Api;

use AppBundle\Entity\Question;
use AppBundle\Entity\Quiz;
use AppBundle\Exceptions\AnswerException;
use AppBundle\Exceptions\QuestionException;
use AppBundle\Repository\AnswerRepository;
use AppBundle\Repository\QuestionRepository;
use AppBundle\Repository\QuizRepository;
use AppBundle\Service\Question\QuestionManager;
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
     * @Route("/admin/api/create/quiz")
     */
    public function createQuizAction(Request $request) : Response
    {
        $content = $request->getContent();
        if(!empty($content)){
            $data = json_decode($content,true);
            $quizRepository = $this->getDoctrine()->getManager()->getRepository("AppBundle\Entity\Quiz");
            $quizRepository->createQuiz($data['quizName'], $data['questions']);
            return new JsonResponse("Hello world");
        }
    }


    /**
     * @Route("/admin/api/question");
     */
    public function getQuestionAction(Request $request){
        $questionName = $request->get("questionName");
        if (isset($questionName))
        {
            $questionRepository = $this->getDoctrine()->getManager()->getRepository("AppBundle\Entity\Question");
            $question = $questionRepository->findOneBy(array("name"=>$questionName));
            $answers = $question->getAnswers();
            $answerNames = array();
            foreach ($answers as $answer){
                $answersArray[$answer->getName()] = $answer->getIsCorrect();
            }
            return new JsonResponse(array("id" => $question->getId(), "answers" => $answersArray, "name" => $question->getName()));
        } else {
            return new Response("Bad request", 400);
        }
    }

    /**
     * @Route("/admin/api/create/question");
     */
    public function createQuestionAction(Request $request)
    {
        $content = $request->getContent();
        if(!empty($content))
        {
            $data = json_decode($content,true);
            if(isset($data['questionName']) && isset($data['answers']))
            {
                try {
                    $questionManager = $this->container->get("question_manager");
                    $questionManager->createQuestion($data['questionName'], $data['answers']);
                    return new JsonResponse("The question was created!");
                } catch (QuestionException $exception) {
                    return new Response($exception->getMessage(), 400);
                } catch (AnswerException $exception) {
                    return new Response($exception->getMessage(), 400);
                }
            } else {
                return new Response("Bad request data!",400);
            }

        }

        return new Response("Bad request data!", 400);
    }

    /**
     * @Route("/api/questions");
     */
    public function getQuestionsAction(Request $request)
    {
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
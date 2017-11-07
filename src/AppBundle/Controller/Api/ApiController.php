<?php

declare(strict_types=1);

namespace AppBundle\Controller\Api;

use AppBundle\Entity\Question;
use AppBundle\Exceptions\AnswerException;
use AppBundle\Exceptions\QuestionException;
use AppBundle\Form\QuestionType;
use AppBundle\Repository\QuestionRepository;
use AppBundle\Service\Quiz\QuizManagerInterface;
use AppBundle\Service\WiredQuestion\WiredQuestionManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
        if(empty($content)){
            return new Response("Bad request data!", 400);
        }

        $arrayContent = json_decode($content,true);


        if(!$this->isCsrfTokenValid("intention",$arrayContent['csrfToken'])){
            return new Response("Invalid CSRF token!", 400);
        }

        if(!$arrayContent['quizName']){
            return new JsonResponse(array("token"=>$arrayContent['csrfToken']), 400);
        }

        if(!$arrayContent['questionsId']){
            return new Response("Empty questions array!", 400);
        }

        if(!count($arrayContent['questionsId'])){
            return new Response("Empty question array count!", 400);
        }

        /** @var QuizManagerInterface $quizManager */
        $quizManager = $this->get("quiz_manager");
        $quiz = $quizManager->createQuiz($arrayContent['quizName'],count($arrayContent['questionsId']));

        /** @var WiredQuestionManagerInterface $wiredQuestionManager */
        $wiredQuestionManager = $this->get("wired_question_manager");

        $wiredQuestionManager->createWiredQuestions($arrayContent['questionsId'],$quiz);

        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse(array("message"=>"Success"));
    }

    /**
     * @Route("/admin/api/block-user", name="_block_user", options={"expose"=true})
     */
    public function blockUserAction(Request $request): Response
    {
        $token = $request->get("token");
        $id = $request->get("id");

        if(!$this->isCsrfTokenValid('intention',$token)) {
            return new Response("Invalid CSRF Token!",400);
        }

        $userRepository = $this->getDoctrine()->getManager()->getRepository("AppBundle\Entity\User");
        $user = $userRepository->findOneBy(array("id" => $id));

        if (!$user) {
            return new Response("Bad request data",400);
        }

        if($user->getId() === $this->getUser()->getId()){
            return new Response("You can't block yourself!",400);
        }

        $user->setEnabled(false);
        $this->getDoctrine()->getManager()->flush();

        return new Response("User was blocked", 200);
    }


    /**
     * @Route("/admin/api/unblock-user", name="_unblock_user", options={"expose"=true})
     */
    public function unBlockUserAction(Request $request): Response
    {
        $token = $request->get("token");
        $id = $request->get("id");

        if(!$this->isCsrfTokenValid("intention", $token)){
            return new Response("Invalid CSRF Token", 400);
        }

        $userRepository = $this->getDoctrine()->getManager()->getRepository("AppBundle\Entity\User");
        $user = $userRepository->findOneBy(array("id" => $id));

        if(!$user){
            return new Response("Bad request data!",400);
        }

        $user->setEnabled(true);
        $this->getDoctrine()->getManager()->flush();
        return new Response("User was unblocked!", 200);
    }


    /**
     * @Route("/admin/api/question");
     */
    public function getQuestionAction(Request $request): Response
    {
        $questionName = $request->get("questionName");

        if(!$questionName){
            return new Response("Bad request data!",400);
        }

        /** @var QuestionRepository $questionRepository */
        $questionRepository = $this->getDoctrine()->getManager()->getRepository("AppBundle\Entity\Question");
        $question = $questionRepository->loadQuestionWithAnswersByName($questionName);

        if(!$question){
            return new Response("Resource not found!",404);
        }

        $answers = $question->getAnswers();

        $answerNames = array();
        foreach ($answers as $answer){
            $answerNames[$answer->getName()] = $answer->IsCorrect();
        }

        return new JsonResponse(array("id" => $question->getId(), "answers" => $answerNames, "name" => $question->getName()));
    }

    /**
     * @Route("/admin/api/create/question");
     */
    public function createQuestionAction(Request $request): Response
    {
        $selected = $request->get("selected");

        if(!$selected){
            return new JsonResponse("Bad request data!", 400);
        }

        $entityManager = $this->getDoctrine()->getManager();

        $question = new Question();
        $form = $this->createForm(QuestionType::class, $question);


        $form->handleRequest($request);

        if($form->isValid()){
            $answers = $question->getAnswers();
            $names = array();
            $isExist = false;
            foreach ($answers as $answer){
                $answerName = $answer->getName();
                if(!$answerName){
                    return new JsonResponse("Bad request data!",400);
                }
                if($answerName === $selected){
                    $answer->setIsCorrect(true);
                    $isExist = true;
                }
                $names[$answer->getName()] = $answer->isCorrect();
            }
            if($isExist)
            {
                $entityManager->persist($question);
                $entityManager->flush();
                return new JsonResponse(array("id"=>$question->getId(),"name"=>$question->getName(), "answers"=>$names));
            }

            return new JsonResponse("Bad suka request!",400);
        }

        return new JsonResponse(array("data"=>$this->getErrorMessages($form)));
    }

    protected function getErrorMessages(\Symfony\Component\Form\Form $form)
    {
        $errors = array();

        foreach ($form->getErrors() as $key => $error) {
            $errors[] = $error->getMessage();
        }

        foreach ($form->all() as $child) {
            if (!$child->isValid()) {
                $errors[$child->getName()] = $this->getErrorMessages($child);
            }
        }

        return $errors;
    }

    /**
     * @Route("/api/questions");
     */
    public function getQuestionsAction(Request $request): Response
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

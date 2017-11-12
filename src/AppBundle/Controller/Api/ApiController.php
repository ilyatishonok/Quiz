<?php

declare(strict_types=1);

namespace AppBundle\Controller\Api;

use AppBundle\Entity\Question;
use AppBundle\Entity\Quiz;
use AppBundle\Entity\User;
use AppBundle\Exceptions\AnswerException;
use AppBundle\Exceptions\QuestionException;
use AppBundle\Form\QuestionType;
use AppBundle\Repository\QuestionRepository;
use AppBundle\Service\Answer\AnswerManagerInterface;
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

        $quizManager = $this->get("quiz_manager");
        $quiz = $quizManager->createQuiz($arrayContent['quizName'],count($arrayContent['questionsId']));

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

        if (!$request->isXmlHttpRequest()) {
            return new Response("Bad request data!", 400);
        }

        if (!$this->isCsrfTokenValid('intention',$token)) {
            return new Response("Invalid CSRF Token!",400);
        }

        $userRepository = $this->getDoctrine()->getManager()->getRepository(User::class);
        $user = $userRepository->findOneBy(array("id" => $id));

        if (!$user) {
            return new Response("Bad request data",400);
        }

        if ($user->getId() === $this->getUser()->getId()) {
            return new Response("You can't block yourself!",400);
        }

        $user->setEnabled(false);
        $this->getDoctrine()->getManager()->flush();

        return new Response("User was blocked", 200);
    }


    /**
     * @Route("/admin/api/block-quiz", name="_block_quiz", options={"expose"=true})
     */
    public function blockQuizAction(Request $request)
    {
        $id = $request->get("id");
        $token = $request->get("token");

        if (!$request->isXmlHttpRequest()) {
            return new Response("Bad request data!", 400);
        }

        if (!$this->isCsrfTokenValid('intention',$token)) {
            return new Response("Invalid CSRF Token!",400);
        }

        $quizRepository = $this->getDoctrine()->getManager()->getRepository(Quiz::class);
        $quiz = $quizRepository->findOneBy(array("id" => $id));

        if (!$quiz) {
            return new Response("Bad request data", 400);
        }

        $quiz->setEnabled(false);
        $this->getDoctrine()->getManager()->flush();

        return new Response("Quiz was blocked",200);
    }

    /**
     * @Route("/admin/api/unblock-user", name="_unblock_user", options={"expose"=true})
     */
    public function unBlockUserAction(Request $request): Response
    {
        $token = $request->get("token");
        $id = $request->get("id");

        if (!$request->isXmlHttpRequest()) {
            return new Response("Bad request data!", 400);
        }

        if (!$this->isCsrfTokenValid("intention", $token)) {
            return new Response("Invalid CSRF Token", 400);
        }

        $userRepository = $this->getDoctrine()->getManager()->getRepository(User::class);
        $user = $userRepository->findOneBy(array("id" => $id));

        if (!$user) {
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
     * @Route("/admin/api/create/question", name="_create_question", options={"expose"=true});
     */
    public function createQuestionAction(Request $request): Response
    {
        $selected = $request->get("selected");

        if(!$selected){
            return new JsonResponse(array("message"=>"Bad request data!"), 400);
        }

        $entityManager = $this->getDoctrine()->getManager();

        $question = new Question();
        $form = $this->createForm(QuestionType::class, $question);

        $form->handleRequest($request);

        if($form->isValid()){
            $answers = $question->getAnswers();

            $answerManager = $this->get('answer_manager');
            try{
                $answerManager->correctAnswers($answers,$selected);

                $entityManager->persist($question);
                $entityManager->flush();

                return new JsonResponse(array(
                    "questionName"=>$question->getName(),
                    "answers"=>$answerManager->getNames($answers),
                    "id"=>$question->getId()
                ));
            } catch (AnswerException $exception){
                return new JsonResponse(array("message"=>$exception->getMessage(),"answer"=>true),400);
            }
        }

        return new JsonResponse(array("message"=>$this->getErrorMessages($form), "question"=>true),400);
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

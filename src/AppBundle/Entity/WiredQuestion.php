<?php

declare(strict_types=1);

namespace AppBundle\Entity;

use Doctrine\Common\Proxy\Proxy;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne as ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn as JoinColumn;

/**
 * WiredQuestion
 *
 * @ORM\Table(name="wired_questions")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\WiredQuestionRepository")
 */
class WiredQuestion
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ManyToOne(targetEntity="Quiz")
     * @JoinColumn(name="quiz_id", referencedColumnName="id")
     */
    private $quiz;

    /**
     * @ManyToOne(targetEntity="Question")
     * @JoinColumn(name="question_id", referencedColumnName="id")
     */
    private $question;

    /**
     * @var int
     *
     * @ORM\Column(name="questionNumber", type="integer")
     */
    private $questionNumber;

    public function getId(): int
    {
        return $this->id;
    }

    public function setQuiz(Quiz $quiz): WiredQuestion
    {
        $this->quiz = $quiz;

        return $this;
    }

    public function getQuiz(): Quiz
    {
        return $this->quiz;
    }

    public function setQuestion(Question $question): WiredQuestion
    {
        $this->question = $question;

        return $this;
    }

    public function setQuestionProxy(Proxy $proxy){
        $this->question = $proxy;

        return $this;
    }

    public function getQuestion(): Question
    {
        return $this->question;
    }

    public function setQuestionNumber(int $questionNumber): WiredQuestion
    {
        $this->questionNumber = $questionNumber;

        return $this;
    }

    public function getQuestionNumber(): int
    {
        return $this->questionNumber;
    }
}


<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * WiredQuestion
 *
 * @ORM\Table(name="wired_question")
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
     * @var int
     *
     * @ORM\Column(name="quizId", type="integer")
     */
    private $quiz;

    /**
     * @var int
     *
     * @ORM\Column(name="question", type="integer")
     */
    private $question;

    /**
     * @var int
     *
     * @ORM\Column(name="questionNymber", type="integer")
     */
    private $questionNumber;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set quiz
     *
     * @param string $quiz
     *
     * @return WiredQuestion
     */
    public function setQuiz($quiz)
    {
        $this->quiz = $quiz;

        return $this;
    }

    /**
     * Get quiz
     *
     * @return string
     */
    public function getQuiz()
    {
        return $this->quiz;
    }

    /**
     * Set question
     *
     * @param string $question
     *
     * @return WiredQuestion
     */
    public function setQuestion($question)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Get question
     *
     * @return string
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set questionNymber
     *
     * @param integer $questionNymber
     *
     * @return WiredQuestion
     */
    public function setQuestionNumber($questionNumber)
    {
        $this->questionNumber = $questionNumber;

        return $this;
    }

    /**
     * Get questionNymber
     *
     * @return int
     */
    public function getQuestionNymber()
    {
        return $this->questionNumber;
    }
}

<?php

declare(strict_types=1);

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn as JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne as ManyToOne;

/**
 * StartedQuiz
 *
 * @ORM\Table(name="started_quizes")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StartedQuizRepository")
 */
class StartedQuiz
{
    public function __construct()
    {
        $this->startTime = new \DateTime();
    }

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="startTime", type="datetime")
     */
    private $startTime;

    /**
     * @ManyToOne(targetEntity="Quiz")
     * @JoinColumn(name="quiz_id", referencedColumnName="id")
     */
    private $quiz;

    /**
     * @ManyToOne(targetEntity="User")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     *@var integer
     *
     * @ORM\Column(name="last_question_number", type="integer")
     */
    private $lastQuestionNumber = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="right_answers", type="integer")
     */
    private $rightAnswers = 0;

    /**
     * Get id
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    public function setQuiz(Quiz $quiz): StartedQuiz
    {
        $this->quiz = $quiz;

        return $this;
    }

    public function addRightAnswer(): StartedQuiz
    {
        $this->rightAnswers += 1;

        return $this;
    }

    public function getRightAnswers(): int
    {
        return $this->rightAnswers;
    }

    public function getQuiz(): Quiz
    {
        return $this->quiz;
    }

    public function setLastQuestionNumber(int $lastQuestionNumber): StartedQuiz
    {
        $this->lastQuestionNumber = $lastQuestionNumber;

        return $this;
    }

    public function setUser($user): StartedQuiz
    {
        $this->user = $user;

        return $this;
    }

    public function setStartTime(\DateTime $startTime): StartedQuiz
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getStartTime(): \DateTime
    {
        return $this->startTime;
    }


    public function getLastQuestionNumber(): int
    {
        return $this->lastQuestionNumber;
    }

}

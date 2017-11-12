<?php

declare(strict_types=1);

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne as ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn as JoinColumn;

/**
 * CompletedQuiz
 *
 * @ORM\Table(name="completed_quiz")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CompletedQuizRepository")
 */
class CompletedQuiz
{
    public function __construct()
    {
        $this->endTime = new \DateTime();
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
     * @ORM\Column(name="start_time", type="datetime")
     */
    private $startTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_time", type="datetime")
     */
    private $endTime;

    /**
     * @var int
     *
     * @ORM\Column(name="rightQuestions", type="integer")
     */
    private $rightQuestions;


    /**
     * @ManyToOne(targetEntity="User")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ManyToOne(targetEntity="Quiz")
     * @JoinColumn(name="quiz_id", referencedColumnName="id")
     */
    private $quiz;

    public function getId(): int
    {
        return $this->id;
    }

    public function setStartTime(\DateTime $time): CompletedQuiz
    {
        $this->startTime = $time;

        return $this;
    }

    public function getStartTime(): \DateTime
    {
        return $this->startTime;
    }

    public function getEndTime(): \DateTime
    {
        return $this->endTime;
    }

    public function setRightQuestions(int $rightQuestions): CompletedQuiz
    {
        $this->rightQuestions = $rightQuestions;

        return $this;
    }

    public function getRightQuestions(): int
    {
        return $this->rightQuestions;
    }

    public function setQuiz(Quiz $quiz): CompletedQuiz
    {
        $this->quiz = $quiz;

        return $this;
    }

    public function setUser(UserInterface $user): CompletedQuiz
    {
        $this->user = $user;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getQuiz(): Quiz
    {
        return $this->quiz;
    }
}


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
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateInterval
     *
     * @ORM\Column(name="time", type="dateinterval")
     */
    private $time;

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

    public function setTime(\DateInterval $time): CompletedQuiz
    {
        $this->time = $time;

        return $this;
    }

    public function getTime(): \DateInterval
    {
        return $this->time;
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


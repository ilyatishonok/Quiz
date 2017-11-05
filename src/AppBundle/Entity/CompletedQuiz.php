<?php

declare(strict_types=1);

//TODO TYPEHINT, REFACTORING.

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
     * Set time
     *
     * @param \DateInterval $time
     *
     * @return CompletedQuiz
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get time
     *
     * @return \DateInterval
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set rightQuestions
     *
     * @param integer $rightQuestions
     *
     * @return CompletedQuiz
     */
    public function setRightQuestions($rightQuestions)
    {
        $this->rightQuestions = $rightQuestions;

        return $this;
    }

    /**
     * Get rightQuestions
     *
     * @return int
     */
    public function getRightQuestions()
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
}


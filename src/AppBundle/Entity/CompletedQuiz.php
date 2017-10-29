<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @var dateinterval
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
     * @param dateinterval $time
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
     * @return dateinterval
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
}


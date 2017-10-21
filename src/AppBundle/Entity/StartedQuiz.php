<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn as JoinColumn;
use Doctrine\ORM\Mapping\OneToOne as OneToOne;

/**
 * StartedQuiz
 *
 * @ORM\Table(name="started_quizes")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StartedQuizRepository")
 */
class StartedQuiz
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
     * @var datetime_immutable
     *
     * @ORM\Column(name="startTime", type="datetime_immutable")
     */
    private $startTime;

    /**
     * @OneToOne(targetEntity="Question")
     * @JoinColumn(name="lastquestion_id", referencedColumnName = "id")
     */
    private $lastQuestion;

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
     * Set startTime
     *
     * @param datetime_immutable $startTime
     *
     * @return StartedQuiz
     */
    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;

        return $this;
    }

    /**
     * Get startTime
     *
     * @return datetime_immutable
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * Set lastQuestion
     *
     * @param integer $lastQuestion
     *
     * @return StartedQuiz
     */
    public function setLastQuestion($lastQuestion)
    {
        $this->lastQuestion = $lastQuestion;

        return $this;
    }

    /**
     * Get lastQuestion
     *
     * @return int
     */
    public function getLastQuestion()
    {
        return $this->lastQuestion;
    }
}


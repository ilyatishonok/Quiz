<?php

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
     * @ManyToOne(targetEntity="WiredQuestion")
     * @JoinColumn(name="last_question_id", referencedColumnName="id")
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

    public function setQuiz(Quiz $quiz){
        $this->quiz = $quiz;

        return $this;
    }

    public function setLastQuestion(WiredQuestion $question){
        $this->lastQuestion = $question;

        return $this;
    }

    public function setUser($user){
        $this->user = $user;

        return $this;
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


    public function getLastQuestion(){
        return $this->lastQuestion->getQuestion();
    }

}


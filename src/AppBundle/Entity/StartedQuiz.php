<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn as JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne as ManyToOne;
use Symfony\Component\Validator\Constraints\DateTime;

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
    private $lastQuestionNumber;

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
    public function getId()
    {
        return $this->id;
    }

    public function setQuiz(Quiz $quiz){
        $this->quiz = $quiz;

        return $this;
    }

    public function addRightAnswer()
    {
        $this->rightAnswers += 1;

        return $this;
    }

    public function getRightAnswers(){
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

    public function setUser($user){
        $this->user = $user;

        return $this;
    }

    /**
     * Set startTime
     *
     * @param \DateTime $startTime
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
     * @return \DateTime
     */
    public function getStartTime()
    {
        return $this->startTime;
    }


    public function getLastQuestionNumber(){
        return $this->lastQuestionNumber;
    }

}


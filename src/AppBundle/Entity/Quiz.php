<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToMany as ManyToMany;
use Doctrine\ORM\Mapping\JoinTable as JoinTable;
use Doctrine\ORM\Mapping\JoinColumn as JoinColumn;
use function Sodium\add;

/**
 * Quiz
 *
 * @ORM\Table(name="quizes")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\QuizRepository")
 */
class Quiz
{

    public function __construct()
    {
        $this->questions = new ArrayCollection();
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * Many User have Many Phonenumbers.
     * @ManyToMany(targetEntity="Question")
     * @JoinTable(name="quizes_questions",
     *      joinColumns={@JoinColumn(name="quiz_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="question_id", referencedColumnName="id", unique=true)}
     *      )
     */
    private $questions;

    public function getQustions(){
        return $this->questions;
    }

    public function getQuestion($position){
        return $this->questions[$position];
    }

    /**
     * @var string
     *
     * @ORM\Column(name="leader", type="string", nullable=true)
     */
    private $leader;

    /**
     * @var string
     *
     * @ORM\Column(name="countOfQuestions", type="integer", length=255)
     */
    private $countOfQuestions;

    /**
     * @var int
     *
     * @ORM\Column(name="countOfPlayers", type="integer")
     */
    private $countOfPlayers;

    /**
     * @var boolean
     *
     * @ORM\Column(name="enabled", type="boolean")
     */
    private $enabled;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function isEnable(){
        return $this->enabled;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Quiz
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set firstQuestionID
     *
     * @param string $firstQuestionID
     *
     * @return Quiz
     */
    public function setFirstQuestionID($firstQuestionID)
    {
        $this->firstQuestionID = $firstQuestionID;

        return $this;
    }

    public function getLeader(){
        return $this->leader;
    }

    public function addQuestion($question){
        $this->questions->add($question);
    }

    /**
     * Get firstQuestionID
     *
     * @return string
     */

    /**
     * Set lastQuestionID
     *
     * @param string $lastQuestionID
     *
     * @return Quiz
     */
    public function setLastQuestionID($lastQuestionID)
    {
        $this->lastQuestionID = $lastQuestionID;

        return $this;
    }

    /**
     * Get lastQuestionID
     *
     * @return string
     */

    /**
     * Set countOfQuestions
     *
     * @param string $countOfQuestions
     *
     * @return Quiz
     */
    public function setCountOfQuestions($countOfQuestions)
    {
        $this->countOfQuestions = $countOfQuestions;

        return $this;
    }

    /**
     * Get countOfQuestions
     *
     * @return string
     */
    public function getCountOfQuestions()
    {
        return $this->countOfQuestions;
    }

    /**
     * Set countOfPlayers
     *
     * @param integer $countOfPlayers
     *
     * @return Quiz
     */
    public function setCountOfPlayers($countOfPlayers)
    {
        $this->countOfPlayers = $countOfPlayers;

        return $this;
    }

    /**
     * Get countOfPlayers
     *
     * @return int
     */
    public function getCountOfPlayers()
    {
        return $this->countOfPlayers;
    }
}


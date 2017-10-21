<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Quiz
 *
 * @ORM\Table(name="quizes")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\QuizRepository")
 */
class Quiz
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="firstQuestionID", type="integer", length=255)
     */
    private $firstQuestionID;

    /**
     * @var string
     *
     * @ORM\Column(name="lastQuestionID", type="integer", length=255)
     */
    private $lastQuestionID;

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
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
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

    /**
     * Get firstQuestionID
     *
     * @return string
     */
    public function getFirstQuestionID()
    {
        return $this->firstQuestionID;
    }

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
    public function getLastQuestionID()
    {
        return $this->lastQuestionID;
    }

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


<?php

declare(strict_types=1);

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany as OneToMany;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Question
 *
 * @ORM\Table(name="questions")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\QuestionRepository")
 */
class Question implements \Serializable
{

    public function __construct()
    {
        $this->answers = new ArrayCollection();
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
     * @OneToMany(targetEntity="Answer", mappedBy="question")
     */
    private $answers;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     *
     * @Assert\NotNull
     */
    private $name;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function setAnswers($answers){
        $this->answers = $answers;
    }

    public function getAnswers(){
        return $this->answers;
    }

    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->name,
            $this->answers,
        ));
    }

    public function setId($id){
        $this->id = $id;
    }


    public function unserialize($serialized)
    {
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Question
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
     * Set questionNumber
     *
     * @param integer $questionNumber
     *
     * @return Question
     */
    public function setQuestionNumber($questionNumber)
    {
        $this->questionNumber = $questionNumber;

        return $this;
    }

    public function addAnswer(Answer $answer){
        $this->answers->add($answer);
    }

    /**
     * Get questionNumber
     *
     * @return int
     */
    public function getQuestionNumber()
    {
        return $this->questionNumber;
    }
}


<?php

declare(strict_types=1);

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne as ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn as JoinColumn;

/**
 * UserAnswer
 *
 * @ORM\Table(name="user_answers")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserAnswerRepository")
 */
class UserAnswer
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
     * @var int
     *
     * @ORM\Column(name="questionNumber", type="integer")
     */
    private $questionNumber;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isCorrect", type="boolean")
     */
    private $isCorrect;


    public function getId(): int
    {
        return $this->id;
    }

    public function setQuestionNumber(int $questionNumber): UserAnswer
    {
        $this->questionNumber = $questionNumber;

        return $this;
    }

    public function getQuestionNumber(): int
    {
        return $this->questionNumber;
    }

    public function getQuiz(): Quiz
    {
        return $this->quiz;
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }

    public function setQuiz(Quiz $quiz): UserAnswer{
        $this->quiz = $quiz;

        return $this;
    }

    public function setUser(UserInterface $user): UserAnswer
    {
        $this->user = $user;

        return $this;
    }

    public function isCorrect(): bool
    {
        return $this->isCorrect();
    }

    public function setCorrect($isCorrect): UserAnswer
    {
        $this->isCorrect = $isCorrect;

        return $this;
    }
}

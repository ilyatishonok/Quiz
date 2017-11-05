<?php

declare(strict_types=1);

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne as ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn as JoinColumn;

/**
 * Answer
 *
 * @ORM\Table(name="answers")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AnswerRepository")
 */
class Answer
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var bool
     *
     * @ORM\Column(name="isCorrect", type="boolean")
     */
    private $isCorrect = false;

    /**
     * @ManyToOne(targetEntity="Question", inversedBy="answers")
     * @JoinColumn(name="question_id", referencedColumnName="id")
     */
    private $question;

    public function getId(): int
    {
        return $this->id;
    }

    public function setName(string $name): Answer
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setIsCorrect(bool $isCorrect): Answer
    {
        $this->isCorrect = $isCorrect;

        return $this;
    }

    public function getQuestion(): Question
    {
        return $this->question;
    }

    public function isCorrect(): bool
    {
        return $this->isCorrect;
    }

    public function setQuestion(Question $question): Answer
    {
        $this->question = $question;

        return $this;
    }

}


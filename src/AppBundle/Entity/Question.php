<?php

declare(strict_types=1);

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany as OneToMany;
use Doctrine\ORM\PersistentCollection;
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

    public function getId(): int
    {
        return $this->id;
    }

    public function setAnswers(Collection $answers): Question
    {
        $this->answers = $answers;

        return $this;
    }

    public function getAnswers(): Collection
    {
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

    public function unserialize($serialized)
    {
    }

    public function setName(string $name): Question
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function addAnswer(Answer $answer): Question
    {
        $this->answers->add($answer);

        return $this;
    }
}


<?php
/**
 * Created by PhpStorm.
 * User: Ilixi
 * Date: 03.11.2017
 * Time: 19:07
 */

namespace AppBundle\Choices;

use AppBundle\Entity\Answer;

class AnswerChoice
{
    private $answer;

    public function setAnswer(Answer $answer): AnswerChoice
    {
        $this->answer = $answer;

        return $this;
    }

    public function getAnswer(): ?Answer
    {
        return $this->answer;
    }
}

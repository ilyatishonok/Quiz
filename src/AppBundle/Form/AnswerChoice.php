<?php
/**
 * Created by PhpStorm.
 * User: Ilixi
 * Date: 03.11.2017
 * Time: 19:07
 */

namespace AppBundle\Form;


use AppBundle\Entity\Answer;

class AnswerChoice
{
    private $answer;

    public function setAnswer(Answer $answer){
        $this->answer = $answer;
    }

    public function getAnswer(){
        return $this->answer;
    }
}
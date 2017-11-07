<?php

declare(strict_types=1);

namespace AppBundle\Repository;

use AppBundle\Entity\Question;

class QuestionRepository extends \Doctrine\ORM\EntityRepository
{
    public function createQuestion(string $questionName) {
        $entityManager = $this->getEntityManager();

        $question = new Question();
        $question->setName($questionName);

        $entityManager->persist($question);;

        return $question;
    }

    public function loadQuestionsByRegular(string $regular){
        return $this->createQueryBuilder("q")
            ->where('q.name LIKE :regular')
            ->setParameter("regular",$regular."%")
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
    }

    public function loadQuestionWithAnswersByName(string $name){
        return $this->createQueryBuilder("q")
            ->leftJoin("q.answers", 'a')
            ->where("q.name=:name")
            ->setParameter('name',$name)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function loadQuestionWithAnswers($id){
        return $this->createQueryBuilder("q")
            ->leftJoin("q.answers", 'a')
            ->where("q.id=:id")
            ->setParameter('id',$id)
            ->getQuery()
            ->getOneOrNullResult();
    }
}

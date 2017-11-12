<?php

declare(strict_types=1);

namespace AppBundle\Repository;

use AppBundle\Entity\Quiz;
use AppBundle\Entity\StartedQuiz;
use AppBundle\Entity\UserInterface;
use AppBundle\Entity\WiredQuestion;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class StartedQuizRepository extends EntityRepository
{
    //Todo Remove this method to StartedQuizManager
    public function createStartedQuiz(UserInterface $user, Quiz $quiz)
    {
        $wiredQuestionRepository = $this->getEntityManager()->getRepository("AppBundle\Entity\WiredQuestion");

        /** @var WiredQuestion $firstQuestion */
        $firstQuestion = $wiredQuestionRepository->findOneBy(array("user"=>$user,"quiz"=>$quiz,"questionNumber"=>0));

        $startedQuiz = new StartedQuiz();
        $startedQuiz->setUser($user);
        $startedQuiz->setQuiz($quiz);
        $startedQuiz->setLastQuestion($firstQuestion);

        $this->getEntityManager()->persist($startedQuiz);
        $this->getEntityManager()->flush();

        return $startedQuiz;
    }

    public function createLoaderQueryByUserAndName(UserInterface $user, string $name): Query
    {
        return $this->createQueryBuilder("sq")
            ->select("sq","q")
            ->leftJoin("sq.quiz", "q")
            ->where("sq.user = :user")
            ->andWhere("q.name LIKE :name")
            ->setParameters(array(
                "user"=>$user,
                "name"=>$name."%",
            ))
            ->getQuery();
    }

    public function createLoaderQueryByUser(UserInterface $user): Query
    {
        return $this->createQueryBuilder("sq")
            ->select("sq","q")
            ->leftJoin("sq.quiz","q")
            ->where("sq.user = :user")
            ->setParameter("user", $user)
            ->getQuery();
    }
}

<?php

declare(strict_types=1);

namespace AppBundle\Repository;

use AppBundle\Entity\Quiz;
use AppBundle\Entity\StartedQuiz;
use AppBundle\Entity\User;
use AppBundle\Entity\UserInterface;
use AppBundle\Entity\WiredQuestion;
use Doctrine\DBAL\Types\DateTimeImmutableType;
use Doctrine\ORM\Query;
use Symfony\Component\Validator\Constraints\DateTime;

class StartedQuizRepository extends \Doctrine\ORM\EntityRepository
{
    public function createStartedQuiz(UserInterface $user, Quiz $quiz){
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

    public function createLoaderQuery(UserInterface $user): Query
    {
        return $this->createQueryBuilder("s")
            ->select("s","q")
            ->leftJoin("s.quiz","q")
            ->where("s.user = :user")
            ->setParameter("user", $user)
            ->getQuery();
    }
}

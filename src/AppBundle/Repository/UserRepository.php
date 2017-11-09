<?php

declare(strict_types=1);

namespace AppBundle\Repository;

use AppBundle\Entity\UserInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

class UserRepository extends EntityRepository implements UserLoaderInterface
{
    public function loadUserByUsername($username): UserInterface
    {
        $user = $this->findOneByUsernameOrEmail($username);
        if (!$user) {
            throw new UsernameNotFoundException('No user found for username or email ' . $username);
        }
        return $user;
    }

    public function createLoaderQuery(): Query
    {
        return $this->createQueryBuilder("u")
            ->getQuery();
    }

    public function findOneByUsernameOrEmail(string $username)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.username = :username OR u.email = :username')
            ->setParameter('username', $username)
            ->getQuery()
            ->getOneOrNullResult();
    }
}

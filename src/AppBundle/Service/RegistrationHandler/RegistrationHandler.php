<?php

declare(strict_types=1);

namespace AppBundle\Service\RegistrationHandler;

use AppBundle\Entity\User;
use AppBundle\Entity\UserInterface;
use AppBundle\Exceptions\UserException;
use Doctrine\ORM\EntityManagerInterface;

class RegistrationHandler
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function confirmEmailByToken(string $token): UserInterface
    {
        $userRepository = $this->entityManager->getRepository(User::class);

        /** @var User $user */
        $user = $userRepository->findOneBy(array("confirmationToken" => $token));

        if ($user) {
            $user->setEnabled(true);
            $user->setConfirmationToken(null);

            $this->entityManager->flush();

            return $user;

        } else {
            throw new UserException("User with $token confirmation token not found!");
        }
    }
}

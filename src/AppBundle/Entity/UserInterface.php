<?php


namespace AppBundle\Entity;

use Symfony\Component\Security\Core\User\AdvancedUserInterface;

interface UserInterface extends AdvancedUserInterface
{
    const ROLE_DEFAULT = 'ROLE_USER';

    const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';

    public function getId();

    public function setUsername($username);

    public function getEmail();

    public function setEmail($email);

    public function getPlainPassword();

    public function setPlainPassword($password);

    public function setPassword($password);

    public function setEnabled($boolean);

    public function getConfirmationToken();

    public function setConfirmationToken($confirmationToken);

    public function getResettingToken();

    public function setResettingToken($resettingToken);

    public function isPasswordRequestNonExpired($ttl);

}

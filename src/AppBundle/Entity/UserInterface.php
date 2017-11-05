<?php

declare(strict_types=1);

namespace AppBundle\Entity;

use Symfony\Component\Security\Core\User\AdvancedUserInterface;

interface UserInterface extends AdvancedUserInterface
{
    public function getId(): ?int;

    public function setUsername(string $username): UserInterface;

    public function getUsername(): ?string;

    public function getEmail(): ?string;

    public function setEmail(string $email): UserInterface;

    public function getPlainPassword(): ?string;

    public function setPlainPassword(string $password): UserInterface;

    public function setPassword(string $password): UserInterface;

    public function getPassword(): ?string;

    public function getRole(): ?string;

    public function setEnabled(bool $enabled): UserInterface;

    public function getConfirmationToken(): ?string;

    public function setConfirmationToken(string $confirmationToken): UserInterface;

    public function getResettingToken(): ?string;

    public function setResettingToken(string $resettingToken): UserInterface;

    public function isPasswordRequestNonExpired($ttl);
}

<?php

namespace AppBundle\Service\Mailer;

use AppBundle\Entity\UserInterface;


interface MailerInterface
{

    public function sendConfirmationEmailMessage(UserInterface $user): void;

    public function sendResettingEmailMessage(UserInterface $user): void;
}

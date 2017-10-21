<?php

namespace AppBundle\Service\Mailer;

use AppBundle\Entity\UserInterface;


interface MailerInterface
{

    public function sendConfirmationEmailMessage(UserInterface $user);

    public function sendResettingEmailMessage(UserInterface $user);
}

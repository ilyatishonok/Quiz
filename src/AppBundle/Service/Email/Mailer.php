<?php

namespace AppBundle\Service\Email;

use AppBundle\Entity\UserInterface;


interface Mailer
{

    public function sendConfirmationEmailMessage(UserInterface $user);

    public function sendResettingEmailMessage(UserInterface $user);
}

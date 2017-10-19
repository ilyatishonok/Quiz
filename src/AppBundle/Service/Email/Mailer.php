<?php

namespace AppBundle\Service\Email;

use AppBundle\Entity\UserInterface;


interface Mailer
{
    /**
     * Send an email to a user to confirm the account creation.
     *
     * @param UserInterface $user
     */
    public function sendConfirmationEmailMessage(UserInterface $user);

    /**
     * Send an email to a user to confirm the password reset.
     *
     * @param UserInterface $user
     */
    public function sendResettingEmailMessage(UserInterface $user);
}

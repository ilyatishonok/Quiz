<?php

declare(strict_types=1);

namespace AppBundle\Service\Mailer;

use AppBundle\Entity\UserInterface;
use AppBundle\Service\Mailer\MailerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class TwigMailer implements MailerInterface
{
    protected $mailer;
    protected $router;
    protected $twig;
    protected $serverName;
    protected $templateName;

    public function __construct(\Swift_Mailer $mailer, UrlGeneratorInterface $router, \Twig_Environment $twig, string $serverName, string $templateName)
    {
        $this->mailer = $mailer;
        $this->router = $router;
        $this->twig = $twig;
        $this->serverName = $serverName;
        $this->templateName = $templateName;
    }

    public function sendConfirmationEmailMessage(UserInterface $user)
    {
        $url = $this->router->generate('email_confirm', array('token' => $user->getConfirmationToken()), UrlGeneratorInterface::ABSOLUTE_URL);

        $context = array(
            'user' => $user,
            'confirmationUrl' => $url,
        );

        $this->sendMessage($this->templateName, $context, $this->serverName, (string) $user->getEmail());
    }


    public function sendResettingEmailMessage(UserInterface $user)
    {

    }

    protected function sendMessage($templateName, $context, $fromEmail, $toEmail)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('Some Subject')
            ->setFrom($fromEmail)
            ->setTo('tishonook@ya.ru')
            ->setBody('mailer\mail.html.twig', 'text/html');
        $this->mailer->send($message);
    }
}
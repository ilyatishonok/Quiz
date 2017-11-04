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
    protected $options;

    public function __construct(\Swift_Mailer $mailer, UrlGeneratorInterface $router, \Twig_Environment $twig, string $serverName, array $options)
    {
        $this->mailer = $mailer;
        $this->router = $router;
        $this->twig = $twig;
        $this->serverName = $serverName;
        $this->options = $options;
    }

    public function sendConfirmationEmailMessage(UserInterface $user)
    {
        $url = $this->router->generate('email_confirm', array('token' => $user->getConfirmationToken()), UrlGeneratorInterface::ABSOLUTE_URL);

        $context = array(
            'user' => $user,
            'confirmationUrl' => $url,
        );

        $this->sendMessage($this->options['email_confirm_template'], $context, $this->serverName, (string) $user->getEmail());
    }


    public function sendResettingEmailMessage(UserInterface $user)
    {
        $url = $this->router->generate('_resetting', array('token' => $user->getResettingToken()), UrlGeneratorInterface::ABSOLUTE_URL);

        $context = array(
            'user' => $user,
            'resettingUrl' => $url,
        );

        $this->sendMessage($this->options['resetting_confirm_template'], $context, $this->serverName, (string) $user->getEmail());
    }

    protected function sendMessage($templateName, $context, $fromEmail, $toEmail)
    {
        $template = $this->twig->load($templateName);
        $subject = $template->renderBlock('subject', $context);
        $textBody = $template->renderBlock('body_text', $context);

        $htmlBody = '';

        if ($template->hasBlock('body_html', $context)) {
            $htmlBody = $template->renderBlock('body_html', $context);
        }

        $message = (new \Swift_Message())
            ->setSubject($subject)
            ->setFrom($fromEmail)
            ->setTo($toEmail);

        if (!empty($htmlBody)) {
            $message->setBody($htmlBody, 'text/html')
                ->addPart($textBody, 'text/plain');
        } else {
            $message->setBody($textBody);
        }

        $this->mailer->send($message);
    }
}

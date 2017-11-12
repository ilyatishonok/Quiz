<?php

declare(strict_types=1);

namespace AppBundle\Controller\Registration;

use AppBundle\Entity\User;
use AppBundle\Exceptions\UserException;
use AppBundle\Form\UserType;
use AppBundle\Service\RegistrationHandler\RegistrationHandler;
use AppBundle\Service\TokenHandler\TokenGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends Controller
{
    /**
     * @Route("/register", name="registration")
     */
    public function registerAction(Request $request, UserPasswordEncoderInterface $passwordEncoder) : Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $password = $passwordEncoder->encodePassword($user,$user->getPlainPassword());
            $user->setPassword($password);

            $tokenGenerator = new TokenGenerator();
            $token = $tokenGenerator->createConfirmationToken();
            $user->setConfirmationToken($token);

            $twigMailer = $this->get("twig_mailer");

            $twigMailer->sendConfirmationEmailMessage($user);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();


            return $this->redirectToRoute("_homepage");
        }

        return $this->render(
            'registration/register.html.twig',
            array('form' => $form->createView())
        );

    }

    /**
     * @Route("/confirm", name="email_confirm")
     */
    public function confirmAction(Request $request)
    {
        try {
            $token = $request->get("token");

            if(!$token){
                return new Response("Bad request data",404);
            }

            /** @var RegistrationHandler $registrationHandler */
            $registrationHandler = $this->get("registration_handler");
            try {
                $user = $registrationHandler->confirmEmailByToken($token);
            }catch (UserException $exception){
                return new Response("Bad request data",404);
            }
            $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
            $this->get('security.token_storage')->setToken($token);
            $this->get('session')->set('_security_main', serialize($token));

            return $this->render("registration/email_confirmed.html.twig", array("error"=>null));
        } catch (UserException $exception) {
            return $this->render("registration/email_confirmed.html.twig", array("error"=>$exception->getMessage()));
        }
    }

}

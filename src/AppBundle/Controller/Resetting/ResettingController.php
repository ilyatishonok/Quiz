<?php

declare(strict_types=1);

namespace AppBundle\Controller\Resetting;

use AppBundle\Choices\EmailChoice;
use AppBundle\Entity\User;
use AppBundle\Form\ResettingType;
use AppBundle\Form\ResetPasswordType;
use AppBundle\Service\TokenHandler\TokenGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ResettingController extends Controller
{
    /**
     * @Route("/resetting", name="_resetting")
     */
    public function resettingPasswordAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $token = $request->get("token");

        $userRepository = $this->getDoctrine()->getManager()->getRepository("AppBundle\Entity\User");
        $user = $userRepository->findOneBy(array("resettingToken"=>$token));

        if(!$user){
            return $this->render("security/user_by_token_not_found.html.twig");
        }

        $form = $this->createForm(ResettingType::class,$user);
        $form->handleRequest($request);

        if($form->isValid() && $form->isSubmitted()){
            $password = $passwordEncoder->encodePassword($user,$user->getPlainPassword());
            $user->setPassword($password);
            $user->setResettingToken(null);

            $this->getDoctrine()->getManager()->persist($user);
            $this->getDoctrine()->getManager()->flush();

            return $this->render("resetting/resetting.html.twig", array("form"=>$form->createView()));
        }

        return $this->render("resetting/resetting.html.twig", array("form"=>$form->createView()));
    }

    /**
     * @Route("/reset", name="_reset")
     */
    public function resetAction(Request $request)
    {
        $emailChoice = new EmailChoice();

        $form = $this->createForm(ResetPasswordType::class, $emailChoice);
        $form->handleRequest($request);

        if($form->isValid() && $form->isSubmitted()){

            $userRepository = $this->getDoctrine()->getManager()->getRepository("AppBundle\Entity\User");
            $user = $userRepository->findOneBy(array("email"=>$emailChoice->getEmail()));

            //TODO USERNOTFOUND
            if(!$user){
                $form->addError(new FormError("User with this email doesn't exist!"));
                return $this->render("resetting/reset.html.twig",array("form"=>$form->createView()));
            }

            $tokenGenerator = new TokenGenerator();
            $token = $tokenGenerator->createConfirmationToken();

            $user->setResettingToken($token);

            $twigMailer = $this->get("twig_mailer");
            $twigMailer->sendResettingEmailMessage($user);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return new Response("A message has been sent to your email address.");
        }

        return $this->render('resetting/reset.html.twig', array('form' => $form->createView()));
    }

}

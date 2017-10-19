<?php

namespace AppBundle\Controller\Registration;

use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends Controller
{
    /**
     * @Route("/register")
     */
    public function registerAction(Request $request, UserPasswordEncoderInterface $passwordEncoder) : Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $password = $passwordEncoder->encodePassword($user,$user->getPlainPassword());
            $user->setPassword($password);
            $user->setLastname("Tishonok");
            $user->setFirstname("Ilia");

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();


            return $this->redirectToRoute("homepage");
        }

        return $this->render(
            'registration/register.html.twig',
            array('form' => $form->createView())
        );

    }

    /**
     * @Route("/checkEmail")
     */
    public function checkEmailAction()
    {

    }

    /**
     * @Route("/confirm")
     */
    public function confirmAction()
    {

    }

    /**
     * @Route("/confirmed")
     */
    public function confirmedAction()
    {

    }

}

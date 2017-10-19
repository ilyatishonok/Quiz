<?php

declare(strict_types=1);

namespace AppBundle\Controller\Security;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request, AuthenticationUtils $authUtils): Response
    {
        $error = $authUtils->getLastAuthenticationError();

        $lastUserName = $authUtils->getLastUsername();

        return $this->render('security/login.html.twig', array(
            'lastUsername' => $lastUserName,
            'error'         => $error,
        ));
    }

    /**
     * @Route("/logout")
     */
    public function logoutAction(Request $request){

    }
}
<?php

namespace AppBundle\Controller\Resetting;

use AppBundle\Form\ResettingType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ResettingController extends Controller
{
    /**
     * @Route("/request")
     */
    public function requestAction()
    {

    }

    /**
     * @Route("/sendEmail")
     */
    public function sendEmailAction()
    {

    }

    /**
     * @Route("/checkEmail")
     */
    public function checkEmailAction()
    {

    }

    /**
     * @Route("/reset?", name="resetting")
     */
    public function resetAction(Request $request, $email)
    {
        $userRepository = $this->getDoctrine()->getManager()->getRepository("AppBundle\Entity\User");
        $user = $userRepository->findOneBy(array("email"=>$email));

        $form = $this->createForm(ResettingType::class);
        $form->setData($user);

        if($form->isValid() && $form->isSubmitted()){
            return new Response("Blath");
        }

        return $this->render('security/resetting.html.twig', array('form' => $form->createView()));
    }

}

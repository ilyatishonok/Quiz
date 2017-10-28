<?php

namespace AppBundle\Controller\Home;

use AppBundle\Entity\Question;
use AppBundle\Entity\Quiz;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function showHomePageAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $properties = $em->getClassMetadata('AppBundle\Entity\Quiz')->getFieldNames();
        $output = array_merge(
            $properties,
            $em->getClassMetadata('AppBundle\Entity\Quiz')->getAssociationNames()
        );

        return new JsonResponse($output);
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }
}

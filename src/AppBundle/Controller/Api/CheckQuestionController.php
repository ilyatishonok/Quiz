<?php

declare(strict_types=1);

namespace AppBundle\Controller\Api;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;


class CheckQuestionController extends Controller
{

    /**
     * @Route("/api/check-question/{id}")
     */
    public function checkQuestionAction(string $id) : Response
    {

    }
}
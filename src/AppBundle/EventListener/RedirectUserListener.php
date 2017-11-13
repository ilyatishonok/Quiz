<?php

declare(strict_types=1);

namespace AppBundle\EventListener;

use AppBundle\Entity\UserInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


class RedirectUserListener
{
    private $tokenStorage;
    private $router;

    public function __construct(TokenStorageInterface $tokenStorage, RouterInterface $router)
    {
        $this->tokenStorage = $tokenStorage;
        $this->router = $router;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if($event->isMasterRequest())
        {
            $currentRoute = $event->getRequest()->attributes->get("_route");
            if($this->isDebugRoute($currentRoute))
            {
                return;
            }
            if(!$this->isUserLogged())
            {
                return;
            }

            if ($this->isAuthenticatedUserOnAnonymousPage($currentRoute)) {
                $response = new RedirectResponse($this->router->generate('_homepage'));
                $event->setResponse($response);
            }
        }
    }

    private function isUserLogged()
    {
        $user = $this->tokenStorage->getToken()->getUser();
        return $user instanceof UserInterface;
    }

    private function isDebugRoute(string $currentRoute)
    {
        return in_array(
            $currentRoute,
            ['_wdt','_profiler']
        );
    }

    private function isAuthenticatedUserOnAnonymousPage($currentRoute)
    {
        return in_array(
            $currentRoute,
            ['login', 'registration', 'email_confirm', '_reset', "_resetting"]
        );
    }
}

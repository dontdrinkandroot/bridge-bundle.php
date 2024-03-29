<?php

namespace Dontdrinkandroot\BridgeBundle\Controller\Security;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginAction extends AbstractController
{
    public function __construct(
        private readonly AuthenticationUtils $authenticationUtils,
        private readonly bool $loginLinkEnabled
    ) {
    }

    public function __invoke(): Response
    {
        /* get the login error if there is one */
        $error = $this->authenticationUtils->getLastAuthenticationError();

        /* last username entered by the user */
        $lastUsername = $this->authenticationUtils->getLastUsername();

        return $this->render('@DdrBridge/Security/login.html.twig', [
            'last_username'     => $lastUsername,
            'error'             => $error,
            'loginLinkEnabled'  => $this->loginLinkEnabled,
        ]);
    }
}

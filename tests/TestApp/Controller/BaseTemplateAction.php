<?php

namespace Dontdrinkandroot\BridgeBundle\Tests\TestApp\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BaseTemplateAction extends AbstractController
{
    public function __invoke(Request $request): Response
    {
        return $this->render('@DdrBridge/base.html.twig');
    }
}

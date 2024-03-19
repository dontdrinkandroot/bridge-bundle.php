<?php

namespace Dontdrinkandroot\BridgeBundle\Controller\Security\LoginLink;

use Dontdrinkandroot\BridgeBundle\Model\Container\RouteName;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class CheckAction extends AbstractController
{
    public function __invoke(): never
    {
        throw new LogicException('This code should never be reached');
    }
}

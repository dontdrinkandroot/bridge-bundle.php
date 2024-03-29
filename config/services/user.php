<?php

namespace Dontdrinkandroot\BridgeBundle\Config;

use Dontdrinkandroot\BridgeBundle\Command\User\EditCommand;
use Dontdrinkandroot\BridgeBundle\Controller\Security\LoginAction;
use Dontdrinkandroot\BridgeBundle\Model\Container\ParamName;
use Dontdrinkandroot\BridgeBundle\Repository\User\UserRepository;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\param;

return function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services->set(UserRepository::class, UserRepository::class)
        ->autoconfigure()
        ->autowire()
        ->arg('$entityClass', param(ParamName::USER_CLASS));

    $services->set(EditCommand::class, EditCommand::class)
        ->autoconfigure()
        ->autowire()
        ->arg('$userClass', param(ParamName::USER_CLASS))
        ->tag('console.command');

    $services->set(LoginAction::class)
        ->autoconfigure()
        ->autowire()
        ->arg('$loginLinkEnabled', param(ParamName::USER_LOGIN_LINK_ENABLED))
        ->tag('controller.service_arguments');
};

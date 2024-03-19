<?php

namespace Dontdrinkandroot\BridgeBundle\Routing;

use Dontdrinkandroot\BridgeBundle\Controller\HealthAction;
use Dontdrinkandroot\BridgeBundle\Controller\Security\LoginAction;
use Dontdrinkandroot\BridgeBundle\Controller\Security\LoginLink\CheckAction;
use Dontdrinkandroot\BridgeBundle\Controller\Security\LoginLink\RequestAction;
use Dontdrinkandroot\BridgeBundle\Model\Container\RouteName;
use Dontdrinkandroot\BridgeBundle\Model\Container\RoutePath;
use Override;
use RuntimeException;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class DdrBridgeLoader extends Loader
{
    public const string TYPE = 'ddr_bridge';

    private bool $isLoaded = false;

    public function __construct(
        private readonly bool $userEnabled,
        private readonly bool $loginLinkEnabled
    ) {
        parent::__construct();
    }

    #[Override]
    public function supports(mixed $resource, ?string $type = null): bool
    {
        return self::TYPE === $type;
    }

    #[Override]
    public function load(mixed $resource, ?string $type = null): RouteCollection
    {
        if (true === $this->isLoaded) {
            throw new RuntimeException('Do not add the "' . self::TYPE . '" loader twice');
        }

        $routes = new RouteCollection();
        $routes->add(
            RouteName::HEALTH,
            new Route(
                path: RoutePath::HEALTH,
                defaults: [
                    '_controller' => HealthAction::class
                ],
                methods: ['GET']
            )
        );

        if ($this->userEnabled) {
            $routes->add(
                RouteName::SECURITY_LOGIN,
                new Route(
                    path: RoutePath::SECURITY_LOGIN,
                    defaults: [
                        '_controller' => LoginAction::class
                    ],
                    methods: ['GET', 'POST']
                )
            );
        }

        if ($this->loginLinkEnabled) {
            $routes->add(
                RouteName::SECURITY_LOGIN_LINK_REQUEST,
                new Route(
                    path: RoutePath::SECURITY_LOGIN_LINK_REQUEST,
                    defaults: [
                        '_controller' => RequestAction::class
                    ],
                    methods: ['GET', 'POST']
                )
            );
            $routes->add(
                RouteName::SECURITY_LOGIN_LINK_CHECK,
                new Route(
                    path: RoutePath::SECURITY_LOGIN_LINK_CHECK,
                    defaults: [
                        '_controller' => CheckAction::class
                    ],
                    methods: ['GET']
                )
            );
        }

        $this->isLoaded = true;

        return $routes;
    }
}

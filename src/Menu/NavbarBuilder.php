<?php

namespace Dontdrinkandroot\BridgeBundle\Menu;

use Dontdrinkandroot\BootstrapBundle\Model\ItemExtra;
use Dontdrinkandroot\BridgeBundle\Event\ConfigureNavbarLeftEvent;
use Dontdrinkandroot\BridgeBundle\Event\ConfigureNavbarRightEvent;
use Dontdrinkandroot\BridgeBundle\Model\Container\RouteName;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\SecurityBundle\Security;

class NavbarBuilder
{
    public function __construct(
        private readonly FactoryInterface $factory,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly bool $userEnabled,
        private readonly ?Security $security = null
    ) {
    }

    public function createLeft(): ItemInterface
    {
        $menu = $this->factory->createItem('root');

        $this->eventDispatcher->dispatch(new ConfigureNavbarLeftEvent($this->factory, $menu));

        return $menu;
    }

    public function createRight(): ItemInterface
    {
        $menu = $this->factory->createItem('root');

        $this->addUserMenu($menu);

        $this->eventDispatcher->dispatch(new ConfigureNavbarRightEvent($this->factory, $menu));

        return $menu;
    }

    protected function addUserMenu(ItemInterface $item): void
    {
        if (!$this->userEnabled || null === ($security = $this->security)) {
            return;
        }

        $user = $security->getUser();
        if (null === $user) {
            $item
                ->addChild('action.login', ['label' => 'login.action', 'route' => RouteName::SECURITY_LOGIN])
                ->setExtra('translation_domain', 'ddr_security');
            return; // Early return
        }

        $userMenu = $item
            ->addChild('User', ['label' => $user->getUserIdentifier()])
            ->setExtra('translation_domain', false);

        if ($security->isGranted('IS_IMPERSONATOR')) {
            $userMenu->setExtra(ItemExtra::ICON, 'bi bi-fw bi-incognito me-2 text-danger');
            $userMenu
                ->addChild('action.end_impersonation', [
                    'route' => 'app.index',
                    'routeParameters' => ['_switch_user' => '_exit'],
                ])
                ->setExtra(ItemExtra::TRANSLATION_DOMAIN, 'ddr_security');
            $userMenu
                ->addChild('impersonation_separator')
                ->setExtra(ItemExtra::DROPDOWN_DIVIDER, true);
        }

        $userMenu
            ->addChild('action.logout', ['label' => 'logout.action', 'route' => RouteName::SECURITY_LOGOUT])
            ->setExtra('translation_domain', 'ddr_security');
    }
}

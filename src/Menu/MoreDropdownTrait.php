<?php

namespace Dontdrinkandroot\BridgeBundle\Menu;

use Dontdrinkandroot\BootstrapBundle\Model\ItemExtra;
use Dontdrinkandroot\BridgeBundle\Model\BootstrapIcon;
use Dontdrinkandroot\BridgeBundle\Model\DdrCrudAdmin\Action;
use Knp\Menu\ItemInterface;

trait MoreDropdownTrait
{
    private function createMoreDropdown(ItemInterface $parent, array $additionalClasses = []): ItemInterface
    {
        $classString = 'ddr-btn-icon ddr-no-caret';
        if (!empty($additionalClasses)) {
            $classString .= ' ' . implode(' ', $additionalClasses);
        }
        return $parent
            ->addChild(Action::MORE, ['label' => ''])
            ->setAttribute('class', $classString)
            ->setExtra(ItemExtra::TRANSLATION_DOMAIN, 'DdrCrudAdmin')
            ->setExtra(ItemExtra::ICON, BootstrapIcon::THREE_DOTS_VERTICAL->toClassString(true));
    }
}

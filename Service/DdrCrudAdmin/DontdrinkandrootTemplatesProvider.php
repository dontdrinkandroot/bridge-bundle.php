<?php

namespace Dontdrinkandroot\BridgeBundle\Service\DdrCrudAdmin;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttributes;
use Dontdrinkandroot\CrudAdminBundle\Service\Template\TemplatesProviderInterface;

class DontdrinkandrootTemplatesProvider implements TemplatesProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function supportsTemplates(CrudAdminContext $context): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function provideTemplates(CrudAdminContext $context): ?array
    {
        $prefix = '@DdrBridge/DdrCrudAdmin/';
        if (RequestAttributes::entityClassMatches($context)) {
            $requestAttributesPrefix = RequestAttributes::getTemplatesPath($context->getRequest());
            if (null !== $requestAttributesPrefix) {
                $prefix = $requestAttributesPrefix;
            }
        }

        return [
            CrudOperation::LIST   => $prefix . 'list.html.twig',
            CrudOperation::READ   => $prefix . 'read.html.twig',
            CrudOperation::CREATE => $prefix . 'update.html.twig',
            CrudOperation::UPDATE => $prefix . 'update.html.twig',
        ];
    }
}

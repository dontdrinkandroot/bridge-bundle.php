<?php

namespace Dontdrinkandroot\BridgeBundle\Service\DdrCrudAdmin;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Service\Template\TemplateProviderInterface;
use Override;

/**
 * @implements TemplateProviderInterface<object>
 */
class DontdrinkandrootTemplateProvider implements TemplateProviderInterface
{
    #[Override]
    public function provideTemplate(string $entityClass, CrudOperation $crudOperation): string
    {
        $prefix = '@DdrBridge/DdrCrudAdmin/';

        return match ($crudOperation) {
            CrudOperation::LIST => $prefix . 'list.html.twig',
            CrudOperation::READ => $prefix . 'read.html.twig',
            CrudOperation::CREATE, CrudOperation::UPDATE => $prefix . 'update.html.twig',
            CrudOperation::DELETE => $prefix . 'delete.html.twig',
        };
    }
}

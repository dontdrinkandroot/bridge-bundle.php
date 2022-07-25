<?php

namespace Dontdrinkandroot\BridgeBundle\Service\DdrCrudAdmin;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Service\Template\TemplateProviderInterface;

class DontdrinkandrootTemplateProvider implements TemplateProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function supportsTemplate(CrudOperation $crudOperation, string $entityClass): bool
    {
        return in_array(
            $crudOperation,
            [CrudOperation::LIST, CrudOperation::READ, CrudOperation::CREATE, CrudOperation::UPDATE],
            true
        );
    }

    /**
     * {@inheritdoc}
     */
    public function provideTemplate(CrudOperation $crudOperation, string $entityClass): string
    {
        $prefix = '@DdrBridge/DdrCrudAdmin/';

        return match ($crudOperation) {
            CrudOperation::LIST => $prefix . 'list.html.twig',
            CrudOperation::READ => $prefix . 'read.html.twig',
            CrudOperation::CREATE => $prefix . 'update.html.twig',
            CrudOperation::UPDATE => $prefix . 'update.html.twig',
        };
    }
}

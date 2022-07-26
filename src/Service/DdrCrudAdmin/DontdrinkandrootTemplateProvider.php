<?php

namespace Dontdrinkandroot\BridgeBundle\Service\DdrCrudAdmin;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Exception\UnsupportedByProviderException;
use Dontdrinkandroot\CrudAdminBundle\Service\Template\TemplateProviderInterface;

class DontdrinkandrootTemplateProvider implements TemplateProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function provideTemplate(CrudOperation $crudOperation, string $entityClass): string
    {
        if (!in_array(
            $crudOperation,
            [CrudOperation::LIST, CrudOperation::READ, CrudOperation::CREATE, CrudOperation::UPDATE],
            true
        )) {
            throw new UnsupportedByProviderException($crudOperation, $entityClass);
        }

        $prefix = '@DdrBridge/DdrCrudAdmin/';

        return match ($crudOperation) {
            CrudOperation::LIST => $prefix . 'list.html.twig',
            CrudOperation::READ => $prefix . 'read.html.twig',
            CrudOperation::CREATE => $prefix . 'update.html.twig',
            CrudOperation::UPDATE => $prefix . 'update.html.twig',
        };
    }
}

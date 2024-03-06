<?php

namespace Dontdrinkandroot\BridgeBundle\Tests\TestApp\Controller;

use Dontdrinkandroot\BridgeBundle\Tests\TestApp\Entity\ExampleEntity;
use Dontdrinkandroot\BridgeBundle\Tests\TestApp\Form\Type\ExampleEntityType;
use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Controller\ConfigurableCrudController;
use Override;

/**
 * @extends ConfigurableCrudController<ExampleEntity>
 */
class ExampleEntityController extends ConfigurableCrudController
{
    #[Override]
    public function getEntityClass(): string
    {
        return ExampleEntity::class;
    }

    #[Override]
    protected function getTemplate(CrudOperation $crudOperation): ?string
    {
        return match ($crudOperation) {
            CrudOperation::LIST => '@DdrBridge/DdrCrudAdmin/list.list-group.html.twig',
            default => null
        };
    }

    #[Override]
    protected function getFormType(): ?string
    {
        return ExampleEntityType::class;
    }
}

<?php

namespace Dontdrinkandroot\BridgeBundle\Tests\TestApp\Controller;

use Dontdrinkandroot\BridgeBundle\Tests\TestApp\Entity\ExampleEntity;
use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Controller\CrudController;
use Dontdrinkandroot\CrudAdminBundle\Service\Template\TemplateProviderInterface;

/**
 * @extends CrudController<ExampleEntity>
 */
class ExampleEntityController extends CrudController implements TemplateProviderInterface
{
    public function __construct()
    {
        parent::__construct(ExampleEntity::class);
    }

    #[\Override]
    public function provideTemplate(string $entityClass, CrudOperation $crudOperation): ?string
    {
        if ($entityClass !== $this->getEntityClass()) {
            return null;
        }

        return match ($crudOperation) {
            CrudOperation::LIST => '@DdrBridge/DdrCrudAdmin/list.list-group.html.twig',
            default => null
        };
    }
}

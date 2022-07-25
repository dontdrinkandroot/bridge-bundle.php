<?php

namespace Dontdrinkandroot\BridgeBundle\Service\DdrCrudAdmin;

use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Service\Id\IdProviderInterface;
use Dontdrinkandroot\DoctrineBundle\Entity\UuidEntityInterface;

class UuidEntityIdProvider implements IdProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function supportsId(CrudOperation $crudOperation, string $entityClass, object $entity): bool
    {
        return ($entity instanceof UuidEntityInterface);
    }

    /**
     * {@inheritdoc}
     */
    public function provideId(CrudOperation $crudOperation, string $entityClass, object $entity): mixed
    {
        return Asserted::instanceOf( $entity, UuidEntityInterface::class)->getUuid()->toBase58();
    }
}

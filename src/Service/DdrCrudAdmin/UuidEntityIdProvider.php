<?php

namespace Dontdrinkandroot\BridgeBundle\Service\DdrCrudAdmin;

use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Service\Id\IdProviderInterface;
use Dontdrinkandroot\DoctrineBundle\Entity\UuidEntityInterface;

class UuidEntityIdProvider implements IdProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function supportsId(string $crudOperation, string $entityClass, object $entity): bool
    {
        return ($entity instanceof UuidEntityInterface);
    }

    /**
     * {@inheritdoc}
     */
    public function provideId(string $crudOperation, string $entityClass, object $entity): mixed
    {
        return Asserted::instanceOf( $entity, UuidEntityInterface::class)->getUuid()->toBase58();
    }
}

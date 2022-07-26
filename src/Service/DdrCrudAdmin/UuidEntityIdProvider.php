<?php

namespace Dontdrinkandroot\BridgeBundle\Service\DdrCrudAdmin;

use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Exception\UnsupportedByProviderException;
use Dontdrinkandroot\CrudAdminBundle\Service\Id\IdProviderInterface;
use Dontdrinkandroot\DoctrineBundle\Entity\UuidEntityInterface;
use Faker\Core\Uuid;

class UuidEntityIdProvider implements IdProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function provideId(CrudOperation $crudOperation, string $entityClass, object $entity): mixed
    {
        if (!($entity instanceof UuidEntityInterface)) {
            throw new UnsupportedByProviderException($crudOperation, $entityClass, $entity);
        }

        return $entity->getUuid()->toBase58();
    }
}

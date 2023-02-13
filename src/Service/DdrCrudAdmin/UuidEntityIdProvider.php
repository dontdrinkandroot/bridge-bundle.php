<?php

namespace Dontdrinkandroot\BridgeBundle\Service\DdrCrudAdmin;

use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Service\Id\IdProviderInterface;
use Dontdrinkandroot\DoctrineBundle\Entity\UuidEntityInterface;
use Faker\Core\Uuid;

class UuidEntityIdProvider implements IdProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function provideId(string $entityClass, CrudOperation $crudOperation, object $entity): mixed
    {
        if (!($entity instanceof UuidEntityInterface)) {
            return null;
        }

        return $entity->getUuid()->toBase58();
    }
}

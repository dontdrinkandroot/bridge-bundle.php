<?php

namespace Dontdrinkandroot\BridgeBundle\Service\DdrCrudAdmin;

use Dontdrinkandroot\Common\CrudOperation;
use Dontdrinkandroot\CrudAdminBundle\Service\Id\IdProviderInterface;
use Dontdrinkandroot\DoctrineBundle\Entity\UuidIdentifiedInterface;
use Override;

/**
 * @implements IdProviderInterface<UuidIdentifiedInterface>
 */
class UuidEntityIdProvider implements IdProviderInterface
{
    #[Override]
    public function provideId(string $entityClass, CrudOperation $crudOperation, object $entity): mixed
    {
        if (!($entity instanceof UuidIdentifiedInterface)) {
            return null;
        }

        return $entity->getUuid()->toBase58();
    }
}

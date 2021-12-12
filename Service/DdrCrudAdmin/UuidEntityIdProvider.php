<?php

namespace Dontdrinkandroot\BridgeBundle\Service\DdrCrudAdmin;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Service\Id\IdProviderInterface;
use Dontdrinkandroot\DoctrineBundle\Entity\UuidEntityInterface;

class UuidEntityIdProvider implements IdProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function supportsId(CrudAdminContext $context): bool
    {
        return ($context->getEntity() instanceof UuidEntityInterface);
    }

    /**
     * {@inheritdoc}
     */
    public function provideId(CrudAdminContext $context)
    {
        $entity = $context->getEntity();
        assert(
            $entity instanceof UuidEntityInterface,
            sprintf('Expected a DefaultUuidEntity, but was a %s', get_class($entity))
        );

        return $entity->getUuid()->toString();
    }
}

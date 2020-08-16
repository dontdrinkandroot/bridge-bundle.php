<?php

namespace Dontdrinkandroot\BridgeBundle\Service\DdrCrudAdmin;

use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Service\Id\IdProviderInterface;
use Dontdrinkandroot\DoctrineBundle\Entity\UuidEntityInterface;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class UuidEntityIdProvider implements IdProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function supports(CrudAdminContext $context)
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

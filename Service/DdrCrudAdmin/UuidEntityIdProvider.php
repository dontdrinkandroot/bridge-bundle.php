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
    public function supportsId(CrudAdminContext $context): bool
    {
        return ($context->getEntity() instanceof UuidEntityInterface);
    }

    /**
     * {@inheritdoc}
     */
    public function provideId(CrudAdminContext $context)
    {
        $entity = Asserted::instanceOf( $context->getEntity(), UuidEntityInterface::class);

        return (string) $entity->getUuid();
    }
}

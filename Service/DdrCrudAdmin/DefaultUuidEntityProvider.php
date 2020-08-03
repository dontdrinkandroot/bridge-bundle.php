<?php

namespace Dontdrinkandroot\BridgeBundle\Service\DdrCrudAdmin;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttributes;
use Dontdrinkandroot\CrudAdminBundle\Service\Id\IdProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Item\ItemProviderInterface;
use Dontdrinkandroot\DoctrineBundle\Entity\DefaultUuidEntity;
use Dontdrinkandroot\DoctrineBundle\Entity\UuidEntityInterface;
use Ramsey\Uuid\Uuid;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class DefaultUuidEntityProvider implements ItemProviderInterface, IdProviderInterface
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(CrudAdminContext $context): bool
    {
        $uuid = RequestAttributes::getId($context->getRequest());
        return ($context->getEntity() instanceof UuidEntityInterface)
            || (is_a($context->getEntityClass(), DefaultUuidEntity::class, true)
                && null !== $uuid
                && Uuid::isValid($uuid));
    }

    /**
     * {@inheritdoc}
     */
    public function provideItem(CrudAdminContext $context): ?object
    {
        $id = RequestAttributes::getId($context->getRequest());
        $entityClass = $context->getEntityClass();
        $entityManager = $this->managerRegistry->getManagerForClass($entityClass);
        assert($entityManager instanceof EntityManagerInterface);
        $persister = $entityManager->getUnitOfWork()->getEntityPersister($entityClass);

        return $persister->load(['uuid' => $id], null, null, [], null, 1);
    }

    /**
     * {@inheritdoc}
     */
    public function provideId(CrudAdminContext $context)
    {
        $entity = $context->getEntity();
        assert($entity instanceof DefaultUuidEntity);

        return $entity->getUuid()->toString();
    }
}

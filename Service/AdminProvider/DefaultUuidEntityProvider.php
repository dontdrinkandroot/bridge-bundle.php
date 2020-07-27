<?php

namespace Dontdrinkandroot\BridgeBundle\Service\AdminProvider;

use Doctrine\DBAL\Types\ConversionException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttributes;
use Dontdrinkandroot\CrudAdminBundle\Service\Id\IdProviderInterface;
use Dontdrinkandroot\CrudAdminBundle\Service\Item\ItemProviderInterface;
use Dontdrinkandroot\DoctrineBundle\Entity\DefaultUuidEntity;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Request;

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
    public function supportsRequest(Request $request): bool
    {
        return is_a(RequestAttributes::getEntityClass($request), DefaultUuidEntity::class, true)
            && Uuid::isValid(RequestAttributes::getId($request));
    }

    /**
     * {@inheritdoc}
     */
    public function provideItem(Request $request): ?object
    {
        $id = RequestAttributes::getId($request);
        $entityClass = RequestAttributes::getEntityClass($request);
        $entityManager = $this->managerRegistry->getManagerForClass($entityClass);
        assert($entityManager instanceof EntityManagerInterface);
        $persister = $entityManager->getUnitOfWork()->getEntityPersister($entityClass);

        return $persister->load(['uuid' => $id], null, null, [], null, 1);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsEntity(object $entity)
    {
        return is_a($entity, DefaultUuidEntity::class);
    }

    /**
     * {@inheritdoc}
     */
    public function provideId(object $entity)
    {
        assert($entity instanceof DefaultUuidEntity);

        return $entity->getUuid()->toString();
    }
}

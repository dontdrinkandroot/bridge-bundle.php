<?php

namespace Dontdrinkandroot\BridgeBundle\Service\AdminProvider;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttributes;
use Dontdrinkandroot\CrudAdminBundle\Service\Item\ItemProviderInterface;
use Dontdrinkandroot\DoctrineBundle\Entity\DefaultUuidEntity;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class DefaultUuidEntityProvider implements ItemProviderInterface
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(Request $request): bool
    {
        return is_a(RequestAttributes::getEntityClass($request), DefaultUuidEntity::class, true);
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
}

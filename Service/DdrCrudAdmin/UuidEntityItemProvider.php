<?php

namespace Dontdrinkandroot\BridgeBundle\Service\DdrCrudAdmin;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\CrudAdminBundle\Model\CrudAdminContext;
use Dontdrinkandroot\CrudAdminBundle\Request\RequestAttributes;
use Dontdrinkandroot\CrudAdminBundle\Service\Item\ItemProviderInterface;
use Dontdrinkandroot\DoctrineBundle\Entity\UuidEntityInterface;
use Symfony\Component\Uid\Uuid;

class UuidEntityItemProvider implements ItemProviderInterface
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsItem(CrudAdminContext $context): bool
    {
        $uuid = RequestAttributes::getId($context->getRequest());
        return null !== $uuid
            && Uuid::isValid($uuid)
            && is_a($context->getEntityClass(), UuidEntityInterface::class, true);
    }

    /**
     * {@inheritdoc}
     */
    public function provideItem(CrudAdminContext $context): ?object
    {
        $id = RequestAttributes::getId($context->getRequest());
        $entityClass = $context->getEntityClass();
        $entityManager = Asserted::instanceOf(
            $this->managerRegistry->getManagerForClass($entityClass),
            EntityManagerInterface::class
        );
        $persister = $entityManager->getUnitOfWork()->getEntityPersister($entityClass);

        return $persister->load(['uuid' => $id], null, null, [], null, 1);
    }
}

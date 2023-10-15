<?php

namespace Dontdrinkandroot\BridgeBundle\Request\ArgumentResolver;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\DoctrineBundle\Entity\UuidInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Uid\Uuid;

class UuidEntityArgumentValueResolver implements ArgumentValueResolverInterface
{
    public function __construct(
        private readonly ManagerRegistry $managerRegistry
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        /** @var class-string|null $type */
        $type = $argument->getType();

        return !$argument->isVariadic()
            && (is_a($type, UuidInterface::class, true))
            && $request->attributes->has('uuid')
            && null !== $this->managerRegistry->getManagerForClass($type);
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $uuidString = $request->attributes->get('uuid');
        $uuid = Uuid::fromString($uuidString);

        /** @var class-string $type */
        $type = $argument->getType();
        $manager = Asserted::instanceOf($this->managerRegistry->getManagerForClass($type), EntityManager::class);
        $entity = $manager->createQueryBuilder()
            ->select('entity')
            ->from($type, 'entity')
            ->where('entity.uuid = :uuid')
            ->setParameter('uuid', $uuid)
            ->getQuery()
            ->getOneOrNullResult();

        if (null === $entity && !$argument->isNullable()) {
            throw new NotFoundHttpException();
        }

        yield $entity;
    }
}

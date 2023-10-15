<?php

namespace Dontdrinkandroot\BridgeBundle\Request\ArgumentResolver;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\DoctrineBundle\Entity\EntityInterface;
use Dontdrinkandroot\DoctrineBundle\Entity\UuidInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Uid\Uuid;

class IdEntityArgumentValueResolver implements ArgumentValueResolverInterface
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
            && $request->attributes->has('id')
            && is_a($type, EntityInterface::class, true)
            && null !== $this->managerRegistry->getManagerForClass($type);
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        /** @var class-string $type */
        $type = $argument->getType();
        $manager = Asserted::instanceOf($this->managerRegistry->getManagerForClass($type), EntityManager::class);
        $entity = $manager->find($type, $request->attributes->get('id'));

        if (null === $entity && !$argument->isNullable()) {
            throw new NotFoundHttpException();
        }

        yield $entity;
    }
}

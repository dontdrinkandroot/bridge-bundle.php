<?php

namespace Dontdrinkandroot\BridgeBundle\Request\ArgumentResolver;

use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Uid\AbstractUid;

use function is_string;

class UidArgumentValueResolver implements ArgumentValueResolverInterface
{
    /**
     * {@inheritdoc}
     */
    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return !$argument->isVariadic()
            && is_string($request->attributes->get($argument->getName()))
            && null !== ($argumentType = $argument->getType())
            && is_subclass_of($argumentType, AbstractUid::class);
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        /** @var class-string<AbstractUid> $uidClass */
        $uidClass = $argument->getType();

        try {
            return [$uidClass::fromString($request->attributes->get($argument->getName()))];
        } catch (InvalidArgumentException $e) {
            throw new NotFoundHttpException(
                sprintf('The uid for the "%s" parameter is invalid.', $argument->getName()),
                $e
            );
        }
    }
}

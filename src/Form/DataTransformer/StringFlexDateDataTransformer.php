<?php

namespace Dontdrinkandroot\BridgeBundle\Form\DataTransformer;

use Dontdrinkandroot\Common\FlexDate;
use Dontdrinkandroot\Common\StringUtils;
use Override;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 * @implements DataTransformerInterface<string, FlexDate>
 */
class StringFlexDateDataTransformer implements DataTransformerInterface
{
    #[Override]
    public function transform(mixed $value): FlexDate
    {
        if (StringUtils::isEmpty($value)) {
            return new FlexDate();
        }

        return FlexDate::fromString($value);
    }

    #[Override]
    public function reverseTransform(mixed $value): ?string
    {
        if (null === $value || $value->isEmpty()) {
            return null;
        }

        return (string)$value;
    }
}

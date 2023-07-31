<?php

namespace Dontdrinkandroot\BridgeBundle\Service\DdrCrudAdmin\FieldRenderer;

use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\Common\Instant;
use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;
use Dontdrinkandroot\CrudAdminBundle\Service\FieldRenderer\FieldRendererProviderInterface;
use Dontdrinkandroot\DoctrineBundle\Type\InstantType;

class InstantFieldRendererProvider implements FieldRendererProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function supports(FieldDefinition $fieldDefinition, mixed $value): bool
    {
        return $fieldDefinition->displayType === InstantType::NAME && null !== $value;
    }

    /**
     * {@inheritdoc}
     */
    public function render(FieldDefinition $fieldDefinition, mixed $value): string
    {
        return Asserted::instanceOf($value, Instant::class)->getDateTime()->format('Y-m-d H:i:s');
    }
}

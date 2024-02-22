<?php

namespace Dontdrinkandroot\BridgeBundle\Service\DdrCrudAdmin\FieldRenderer;

use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\Common\Instant;
use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;
use Dontdrinkandroot\CrudAdminBundle\Service\FieldRenderer\FieldRendererProviderInterface;
use Dontdrinkandroot\DoctrineBundle\Type\InstantType;
use Override;

class InstantFieldRendererProvider implements FieldRendererProviderInterface
{
    #[Override]
    public function supports(FieldDefinition $fieldDefinition, mixed $value): bool
    {
        return $fieldDefinition->displayType === InstantType::NAME && null !== $value;
    }

    #[Override]
    public function render(FieldDefinition $fieldDefinition, mixed $value): string
    {
        return Asserted::instanceOf($value, Instant::class)->getDateTime()->format('Y-m-d H:i:s');
    }
}

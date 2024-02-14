<?php

namespace Dontdrinkandroot\BridgeBundle\Service\DdrCrudAdmin\FieldRenderer;

use Doctrine\DBAL\Types\Types;
use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;
use Dontdrinkandroot\CrudAdminBundle\Service\FieldRenderer\FieldRendererProviderInterface;
use Override;

class FontAwesome5BooleanRendererProvider implements FieldRendererProviderInterface
{
    #[Override]
    public function supports(FieldDefinition $fieldDefinition, mixed $value): bool
    {
        return in_array($fieldDefinition->displayType, [Types::BOOLEAN, 'bool'], true);
    }

    #[Override]
    public function render(FieldDefinition $fieldDefinition, mixed $value): string
    {
        return Asserted::bool($value)
            ? '<span class="bi bi-fw bi-check"></span>'
            : '<span class="bi bi-fw bi-x"></span>';
    }
}

<?php

namespace Dontdrinkandroot\BridgeBundle\Service\DdrCrudAdmin\FieldRenderer;

use Doctrine\DBAL\Types\Types;
use Dontdrinkandroot\Common\Asserted;
use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;
use Dontdrinkandroot\CrudAdminBundle\Service\FieldRenderer\FieldRendererProviderInterface;

class FontAwesome5BooleanRendererProvider implements FieldRendererProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function supports(FieldDefinition $fieldDefinition, mixed $value): bool
    {
        return Types::BOOLEAN === $fieldDefinition->getType();
    }

    /**
     * {@inheritdoc}
     */
    public function render(FieldDefinition $fieldDefinition, mixed $value): string
    {
        return Asserted::bool($value) ? '<span class="fas fa-fw fa-check"></span>' : '<span class="fas fa-fw fa-times"></span>';
    }
}

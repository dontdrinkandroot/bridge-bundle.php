<?php

namespace Dontdrinkandroot\BridgeBundle\Service\DdrCrudAdmin\FieldRenderer;

use Dontdrinkandroot\Common\DateUtils;
use Dontdrinkandroot\CrudAdminBundle\Model\FieldDefinition;
use Dontdrinkandroot\CrudAdminBundle\Service\FieldRenderer\FieldRenderer;
use Dontdrinkandroot\CrudAdminBundle\Service\FieldRenderer\FieldRendererProviderInterface;

class MillisecondsRendererProvider implements FieldRendererProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function supports(FieldDefinition $fieldDefinition, $value): bool
    {
        return 'milliseconds' === $fieldDefinition->type;
    }

    /**
     * {@inheritdoc}
     */
    public function render(FieldDefinition $fieldDefinition, $value): string
    {
        return FieldRenderer::escapeHtml(DateUtils::fromMillis($value)->format('Y-m-d H:i:s'));
    }
}

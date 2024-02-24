<?php

namespace Dontdrinkandroot\BridgeBundle\Model;

enum BootstrapIcon: string
{
    case DOWNLOAD = 'bi-download';
    case PENCIL = 'bi-pencil';
    case SEARCH = 'bi-search';
    case SHARE = 'bi-share';
    case THREE_DOTS_VERTICAL = 'bi-three-dots-vertical';
    case TRASH = 'bi-trash';

    public function toClassString(bool $fixedWith = false, array $additionalClasses = []): string
    {
        $classes = ['bi', $this->value];
        if ($fixedWith) {
            $classes[] = 'bi-fw';
        }
        $classes = array_merge($classes, $additionalClasses);
        return implode(' ', $classes);
    }
}

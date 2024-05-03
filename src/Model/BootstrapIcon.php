<?php

namespace Dontdrinkandroot\BridgeBundle\Model;

enum BootstrapIcon: string
{
    case CHECK = 'bi-check';
    case DOT = 'bi-dot';
    case DOWNLOAD = 'bi-download';
    case INCOGNITO = 'bi-incognito';
    case MUSIC_NOTE_LIST = 'bi-music-note-list';
    case PENCIL = 'bi-pencil';
    case PLUS = 'bi-plus';
    case SEARCH = 'bi-search';
    case SHARE = 'bi-share';
    case THREE_DOTS_VERTICAL = 'bi-three-dots-vertical';
    case UPLOAD = 'bi-upload';
    case TRASH = 'bi-trash';
    case X = 'bi-x';

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

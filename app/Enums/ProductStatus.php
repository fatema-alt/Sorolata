<?php

declare(strict_types=1);

namespace App\Enums;

enum ProductStatus: string
{
    case DRAFT = 'draft';
    case CURATING = 'curating';
    case PUBLISHED = 'published';
    case ARCHIVED = 'archived';
    case PRE_ORDER = 'pre_order';
    case ARCHIVAL_PIECE = 'archival_piece';

    public function isVisibleToPublic(): bool
    {
        return in_array($this, [self::PUBLISHED, self::PRE_ORDER, self::ARCHIVAL_PIECE], true);
    }
}

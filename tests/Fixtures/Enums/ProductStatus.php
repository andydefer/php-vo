<?php

declare(strict_types=1);

namespace AndyDefer\PhpVo\Tests\Fixtures\Enums;

enum ProductStatus: string
{
    case DRAFT = 'draft';
    case ACTIVE = 'active';
    case DISCONTINUED = 'discontinued';
    case OUT_OF_STOCK = 'out_of_stock';

    public function getLabel(): string
    {
        return match ($this) {
            self::DRAFT => 'Brouillon',
            self::ACTIVE => 'Actif',
            self::DISCONTINUED => 'Abandonné',
            self::OUT_OF_STOCK => 'Rupture de stock',
        };
    }

    public function isAvailable(): bool
    {
        return $this === self::ACTIVE;
    }
}

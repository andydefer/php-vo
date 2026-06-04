<?php

declare(strict_types=1);

namespace AndyDefer\PhpVo\Tests\Fixtures\Enums;

enum ProductCategory: string
{
    case ELECTRONICS = 'electronics';
    case CLOTHING = 'clothing';
    case BOOKS = 'books';
    case FOOD = 'food';
    case FURNITURE = 'furniture';

    public function getTaxRate(): float
    {
        return match ($this) {
            self::ELECTRONICS => 0.20,
            self::CLOTHING => 0.10,
            self::BOOKS => 0.055,
            self::FOOD => 0.05,
            self::FURNITURE => 0.20,
        };
    }
}

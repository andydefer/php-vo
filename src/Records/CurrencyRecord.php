<?php

declare(strict_types=1);

namespace AndyDefer\PhpVo\Records;

use AndyDefer\DomainStructures\Abstracts\AbstractRecord;

/**
 * CurrencyRecord - Data container for currency values.
 */
final class CurrencyRecord extends AbstractRecord
{
    public function __construct(
        public readonly string $code,
        public readonly string $symbol,
        public readonly string $name,
        public readonly int $decimals
    ) {}
}

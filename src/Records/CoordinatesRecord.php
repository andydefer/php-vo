<?php

declare(strict_types=1);

namespace AndyDefer\PhpVo\Records;

use AndyDefer\DomainStructures\Abstracts\AbstractRecord;

final class CoordinatesRecord extends AbstractRecord
{
    public function __construct(
        public readonly float $latitude,
        public readonly float $longitude,
    ) {}
}

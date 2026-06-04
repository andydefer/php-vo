<?php

declare(strict_types=1);

namespace AndyDefer\PhpVo\Records;

use AndyDefer\DomainStructures\Abstracts\AbstractRecord;
use AndyDefer\PhpVo\ValueObjects\PostalCode;

final class AddressRecord extends AbstractRecord
{
    public function __construct(
        public readonly string $street,
        public readonly string $city,
        public readonly PostalCode $postalCode,
        public readonly string $country,
    ) {}
}

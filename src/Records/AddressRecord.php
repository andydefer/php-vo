<?php

declare(strict_types=1);

namespace AndyDefer\PhpVo\Records;

use AndyDefer\DomainStructures\Abstracts\AbstractRecord;
use AndyDefer\PhpVo\ValueObjects\PostalCode;
use AndyDefer\PhpVo\ValueObjects\PostalCodeVO;

final class AddressRecord extends AbstractRecord
{
    public function __construct(
        public readonly string $street,
        public readonly string $city,
        public readonly PostalCodeVO $postalCode,
        public readonly string $country,
    ) {}
}

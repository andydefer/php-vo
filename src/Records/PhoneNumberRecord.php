<?php

declare(strict_types=1);

namespace AndyDefer\PhpVo\Records;

use AndyDefer\DomainStructures\Abstracts\AbstractRecord;
use AndyDefer\PhpVo\Enums\CallingCode;

/**
 * Record representing a phone number with calling code and subscriber number.
 */
final class PhoneNumberRecord extends AbstractRecord
{
    public function __construct(
        public readonly CallingCode $callingCode,
        public readonly string $number,
    ) {}
}

<?php

declare(strict_types=1);

namespace AndyDefer\PhpVo\ValueObjects;

use AndyDefer\DomainStructures\Abstracts\AbstractValueObject;
use InvalidArgumentException;

/**
 * Postal Code Value Object.
 *
 * Represents a validated postal code.
 *
 * @example
 * $postalCode = PostalCodeVO::from('75001');
 * echo $postalCode->getValue(); // '75001'
 */
final class PostalCodeVO extends AbstractValueObject
{
    public function __construct(protected readonly string $value)
    {
        $this->validate($value);
    }

    /**
     * Validate the postal code format.
     *
     * Maximum 10 characters, alphanumeric.
     *
     * @param  string  $value  The postal code to validate
     *
     * @throws InvalidArgumentException If the postal code format is invalid
     */
    private function validate(string $value): void
    {
        if (strlen($value) > 10) {
            throw new InvalidArgumentException(sprintf(
                'Invalid postal code: "%s". Maximum 10 characters allowed.',
                $value
            ));
        }

        if (! preg_match('/^[a-zA-Z0-9\s\-]+$/', $value)) {
            throw new InvalidArgumentException(sprintf(
                'Invalid postal code format: "%s". Only alphanumeric characters, spaces and hyphens are allowed.',
                $value
            ));
        }
    }

    /**
     * Returns the raw value of the Postal Code.
     *
     * @return string The postal code
     */
    public function getValue(): string
    {
        return $this->value;
    }
}

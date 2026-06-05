<?php

declare(strict_types=1);

namespace AndyDefer\PhpVo\ValueObjects;

use AndyDefer\DomainStructures\Abstracts\AbstractValueObject;
use InvalidArgumentException;

/**
 * Postal Code Value Object.
 * 
 * Represents a validated 5-digit postal code (French format).
 * 
 * @example
 * $postalCode = PostalCodeVO::from('75001');
 * echo $postalCode->getValue(); // '75001'
 */
final class PostalCodeVO extends AbstractValueObject
{
    public function __construct(protected readonly string $value) {}

    /**
     * Create a PostalCode instance from various source formats.
     *
     * @param mixed $source The source data (string or PostalCode instance)
     * @return static
     * @throws InvalidArgumentException If the postal code format is invalid
     */
    public static function from(mixed $source): static
    {
        if ($source instanceof self) {
            return $source;
        }

        if (!is_string($source)) {
            throw new InvalidArgumentException('Postal code must be a string');
        }

        $value = trim($source);

        if (!preg_match('/^[0-9]{5}$/', $value)) {
            throw new InvalidArgumentException(sprintf(
                'Invalid postal code format: "%s". Must be 5 digits.',
                $value
            ));
        }

        return new self($value);
    }

    /**
     * Returns the raw value of the Postal Code.
     *
     * @return string The 5-digit postal code
     */
    public function getValue(): string
    {
        return $this->value;
    }
}

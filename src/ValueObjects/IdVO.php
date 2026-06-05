<?php

declare(strict_types=1);

namespace AndyDefer\PhpVo\ValueObjects;

use AndyDefer\DomainStructures\Abstracts\AbstractValueObject;
use InvalidArgumentException;

/**
 * Value Object representing a positive integer identifier.
 *
 * Encapsulates a positive integer ID with validation.
 * Useful for user IDs, product IDs, category IDs, etc.
 *
 * @example
 * $id = IdVO::from(123);
 * echo $id->getValue();   // 123
 * echo $id->toReadableString(); // 'ID #123'
 * echo $id->isValid();    // true
 */
final class IdVO extends AbstractValueObject
{
    public function __construct(
        public readonly int $value,
    ) {
        if ($value <= 0) {
            throw new InvalidArgumentException(
                sprintf('ID must be positive, got: %d', $value)
            );
        }
    }

    /**
     * Get the raw ID value.
     *
     * @return int The ID
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * Get the ID as a formatted readable string.
     * Example: 123 → 'ID #123'
     *
     * @return string Human-readable representation
     */
    public function toReadableString(): string
    {
        return sprintf('ID #%d', $this->value);
    }

    /**
     * Get the ID as a zero-padded string.
     * Example: 123 with length 5 → '00123'
     *
     * @param int $length Total length of the padded string
     *
     * @return string Zero-padded ID
     */
    public function toZeroPadded(int $length): string
    {
        return str_pad((string)$this->value, $length, '0', STR_PAD_LEFT);
    }

    /**
     * Check if the ID is valid (always true after construction).
     *
     * @return bool True if valid
     */
    public function isValid(): bool
    {
        return $this->value > 0;
    }

    /**
     * Increment the ID.
     *
     * @return self New IdVO with value + 1
     */
    public function increment(): self
    {
        return new self($this->value + 1);
    }

    /**
     * Decrement the ID.
     *
     * @return self New IdVO with value - 1
     */
    public function decrement(): self
    {
        return new self($this->value - 1);
    }

    public function __toString(): string
    {
        return (string)$this->value;
    }
}

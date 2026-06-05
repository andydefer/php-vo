<?php

declare(strict_types=1);

namespace AndyDefer\PhpVo\ValueObjects;

use AndyDefer\DomainStructures\Abstracts\AbstractValueObject;
use AndyDefer\DomainStructures\Hydration\Strategy\SingleParameterStrategy;
use InvalidArgumentException;

/**
 * Immutable value object representing a monetary amount with 2 decimal precision.
 *
 * Uses bcmath for precise arithmetic operations, avoiding floating point errors.
 * Supports creation from strings, integers, floats, and normalized formats.
 *
 * @example
 * $amount = AmountVO::from('99.99');
 * $total = $amount->add(AmountVO::from('50.00'));
 * echo $total; // "149.99"
 */
final class AmountVO extends AbstractValueObject
{
    private const DECIMALS = 2;

    private readonly string $value;

    public function __construct(string $value)
    {
        SingleParameterStrategy::class;
        $normalized = $this->normalize($value);
        $this->validate($normalized);
        $this->value = $this->formatNumber($normalized);
    }

    /**
     * Returns the amount as float (for serialization).
     *
     * @return float The amount as a float value
     */
    public function getValue(): float
    {
        return (float)$this->value;
    }

    /**
     * Returns the amount as string (for calculations).
     *
     * @return string The amount as a string (e.g., "99.99")
     */
    public function toString(): string
    {
        return $this->value;
    }

    /**
     * Returns the amount as float.
     *
     * @return float The amount as a float
     */
    public function toFloat(): float
    {
        return (float)$this->value;
    }

    /**
     * Returns the amount in cents (integer).
     *
     * @return int The amount multiplied by 100 (e.g., 9999 for 99.99)
     */
    public function toInt(): int
    {
        return (int)round($this->toFloat() * 100);
    }

    /**
     * Adds another amount to this one.
     *
     * @param self $other The amount to add
     *
     * @return self A new instance with the sum
     */
    public function add(self $other): self
    {
        $result = bcadd($this->value, $other->value, self::DECIMALS);
        return new self($result);
    }

    /**
     * Subtracts another amount from this one.
     *
     * @param self $other The amount to subtract
     *
     * @return self A new instance with the difference
     */
    public function subtract(self $other): self
    {
        $result = bcsub($this->value, $other->value, self::DECIMALS);
        return new self($result);
    }

    /**
     * Multiplies the amount by a multiplier.
     *
     * @param float|int|string $multiplier The multiplier value
     *
     * @return self A new instance with the product
     */
    public function multiply(float|int|string $multiplier): self
    {
        $result = bcmul($this->value, (string)$multiplier, self::DECIMALS);
        return new self($result);
    }

    /**
     * Divides the amount by a divisor.
     *
     * @param float|int|string $divisor The divisor value (cannot be zero)
     *
     * @return self A new instance with the quotient
     *
     * @throws InvalidArgumentException If divisor is zero
     */
    public function divide(float|int|string $divisor): self
    {
        if ((float)$divisor === 0.0) {
            throw new InvalidArgumentException('Division by zero');
        }
        $result = bcdiv($this->value, (string)$divisor, self::DECIMALS);
        return new self($result);
    }

    /**
     * Calculates a percentage of the current amount.
     *
     * @param float|int|string $percent The percentage to calculate (e.g., 20 for 20%)
     *
     * @return self A new instance with the percentage value
     */
    public function percentage(float|int|string $percent): self
    {
        $factor = bcdiv((string)$percent, '100', self::DECIMALS + 2);
        return $this->multiply($factor);
    }

    /**
     * Checks if the amount is zero.
     *
     * @return bool True if the amount equals zero
     */
    public function isZero(): bool
    {
        return bccomp($this->value, '0', self::DECIMALS) === 0;
    }

    /**
     * Checks if the amount is positive (greater than zero).
     *
     * @return bool True if the amount is positive
     */
    public function isPositive(): bool
    {
        return bccomp($this->value, '0', self::DECIMALS) > 0;
    }

    /**
     * Checks if the amount is negative (less than zero).
     *
     * @return bool True if the amount is negative
     */
    public function isNegative(): bool
    {
        return bccomp($this->value, '0', self::DECIMALS) < 0;
    }

    /**
     * Normalizes the input string by replacing commas and removing invalid characters.
     *
     * @param string $value The raw input value
     *
     * @return string The normalized value
     */
    private function normalize(string $value): string
    {
        $value = str_replace(',', '.', $value);
        $value = preg_replace('/[^0-9\.\-]/', '', $value);
        return $value;
    }

    /**
     * Validates the amount format.
     *
     * @param string $value The normalized value to validate
     *
     * @throws InvalidArgumentException If the format is invalid
     */
    private function validate(string $value): void
    {
        if (!preg_match('/^-?\d+(\.\d{1,2})?$/', $value)) {
            throw new InvalidArgumentException("Invalid amount format: {$value}");
        }
    }

    /**
     * Formats the number with exactly 2 decimal places.
     *
     * @param string $value The normalized value
     *
     * @return string The formatted value with 2 decimal places
     */
    private function formatNumber(string $value): string
    {
        $parts = explode('.', $value);
        $integer = ltrim($parts[0], '0') ?: '0';

        $sign = '';
        if (str_starts_with($integer, '-')) {
            $sign = '-';
            $integer = substr($integer, 1);
        }

        if (isset($parts[1])) {
            $decimal = substr($parts[1], 0, 2);
            $decimal = str_pad($decimal, 2, '0');
        } else {
            $decimal = '00';
        }

        return $sign . $integer . '.' . $decimal;
    }

    /**
     * Returns the formatted amount as string.
     *
     * @return string The amount as string
     */
    public function __toString(): string
    {
        return $this->value;
    }
}

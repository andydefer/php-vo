<?php

declare(strict_types=1);

namespace AndyDefer\PhpVo\ValueObjects;

use AndyDefer\DomainStructures\Abstracts\AbstractValueObject;
use InvalidArgumentException;

final class Amount extends AbstractValueObject
{
    private const DECIMALS = 2;

    private readonly string $value;

    public function __construct(string $value)
    {
        $normalized = $this->normalize($value);
        $this->validate($normalized);
        $this->value = $this->formatNumber($normalized);
    }

    /**
     * Create Amount from string, int, or float.
     */
    public static function from(mixed $source): static
    {
        if ($source instanceof self) {
            return $source;
        }

        if (is_string($source)) {
            return new self($source);
        }

        if (is_int($source)) {
            return new self((string)$source);
        }

        if (is_float($source)) {
            return new self(number_format($source, self::DECIMALS, '.', ''));
        }

        throw new InvalidArgumentException(
            sprintf('Amount::from expects string, int, or float, got %s', gettype($source))
        );
    }

    /**
     * Returns the amount as float (for serialization).
     */
    public function getValue(): float
    {
        return (float)$this->value;
    }

    /**
     * Returns the amount as string (for calculations).
     */
    public function toString(): string
    {
        return $this->value;
    }

    public function toFloat(): float
    {
        return (float)$this->value;
    }

    public function toInt(): int
    {
        return (int)round($this->toFloat() * 100);
    }

    public function add(self $other): self
    {
        $result = bcadd($this->value, $other->value, self::DECIMALS);
        return new self($result);
    }

    public function subtract(self $other): self
    {
        $result = bcsub($this->value, $other->value, self::DECIMALS);
        return new self($result);
    }

    public function multiply(float|int|string $multiplier): self
    {
        $result = bcmul($this->value, (string)$multiplier, self::DECIMALS);
        return new self($result);
    }

    public function divide(float|int|string $divisor): self
    {
        if ((float)$divisor === 0.0) {
            throw new InvalidArgumentException('Division by zero');
        }
        $result = bcdiv($this->value, (string)$divisor, self::DECIMALS);
        return new self($result);
    }

    public function percentage(float|int|string $percent): self
    {
        $factor = bcdiv((string)$percent, '100', self::DECIMALS + 2);
        return $this->multiply($factor);
    }

    public function isZero(): bool
    {
        return bccomp($this->value, '0', self::DECIMALS) === 0;
    }

    public function isPositive(): bool
    {
        return bccomp($this->value, '0', self::DECIMALS) > 0;
    }

    public function isNegative(): bool
    {
        return bccomp($this->value, '0', self::DECIMALS) < 0;
    }

    private function normalize(string $value): string
    {
        $value = str_replace(',', '.', $value);
        $value = preg_replace('/[^0-9\.\-]/', '', $value);
        return $value;
    }

    private function validate(string $value): void
    {
        if (!preg_match('/^-?\d+(\.\d{1,2})?$/', $value)) {
            throw new InvalidArgumentException("Invalid amount format: {$value}");
        }
    }

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

    public function __toString(): string
    {
        return $this->value;
    }
}

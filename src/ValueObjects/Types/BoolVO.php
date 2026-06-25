<?php

declare(strict_types=1);

namespace AndyDefer\PhpVo\ValueObjects\Types;

use AndyDefer\DomainStructures\Abstracts\AbstractValueObject;

/**
 * Value Object for boolean values.
 *
 * Encapsulates boolean operations with logical methods.
 *
 * @author Andy Defer
 */
class BoolVO extends AbstractValueObject
{
    private bool $value;

    public function __construct(bool $value)
    {
        $this->value = $value;
    }

    public function getValue(): bool
    {
        return $this->value;
    }

    // ========== OPÉRATIONS LOGIQUES ==========

    public function and(mixed $other): self
    {
        return new self($this->value && self::from($other)->getValue());
    }

    public function or(mixed $other): self
    {
        return new self($this->value || self::from($other)->getValue());
    }

    public function xor(mixed $other): self
    {
        return new self($this->value xor self::from($other)->getValue());
    }

    public function not(): self
    {
        return new self(! $this->value);
    }

    public function nand(mixed $other): self
    {
        return new self(! ($this->value && self::from($other)->getValue()));
    }

    public function nor(mixed $other): self
    {
        return new self(! ($this->value || self::from($other)->getValue()));
    }

    public function xnor(mixed $other): self
    {
        return new self($this->value === self::from($other)->getValue());
    }

    // ========== COMPARAISONS ==========

    public function equal(mixed $other): self
    {
        return new self($this->value === self::from($other)->getValue());
    }

    public function notEquals(mixed $other): self
    {
        return new self($this->value !== self::from($other)->getValue());
    }

    // ========== VÉRIFICATIONS ==========

    public function isTrue(): self
    {
        return new self($this->value === true);
    }

    public function isFalse(): self
    {
        return new self($this->value === false);
    }

    // ========== CONVERSION ==========

    public function toInt(): int
    {
        return FloatVO::from($this->value ? 1 : 0)->toInt();
    }

    public function toFloat(): FloatVO
    {
        return FloatVO::from($this->value ? 1.0 : 0.0);
    }

    public function toString(): StringVO
    {
        return StringVO::from($this->value ? 'true' : 'false');
    }

    // ========== OPÉRATIONS CONDITIONNELLES ==========

    public function ifTrue(callable $callback): self
    {
        if ($this->value) {
            $callback($this);
        }

        return $this;
    }

    public function ifFalse(callable $callback): self
    {
        if (! $this->value) {
            $callback($this);
        }

        return $this;
    }

    public function ifElse(callable $ifTrue, callable $ifFalse): mixed
    {
        return $this->value ? $ifTrue($this) : $ifFalse($this);
    }

    // ========== OPÉRATIONS AVEC D'AUTRES TYPES ==========

    public function andWith(callable $evaluation): self
    {
        return new self($this->value && (bool) $evaluation());
    }

    public function orWith(callable $evaluation): self
    {
        return new self($this->value || (bool) $evaluation());
    }

    public function __toString(): string
    {
        return $this->value ? 'true' : 'false';
    }
}

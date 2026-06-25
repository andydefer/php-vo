<?php

declare(strict_types=1);

namespace AndyDefer\PhpVo\ValueObjects\Types;

use AndyDefer\DomainStructures\Abstracts\AbstractValueObject;
use AndyDefer\DomainStructures\Utils\ListCollection;
use InvalidArgumentException;

/**
 * Value Object for numeric values (both integer and float).
 *
 * @author Andy Defer
 */
class FloatVO extends AbstractValueObject
{
    protected float $value;

    public function __construct(float $value = 0.0)
    {
        $this->value = $value;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    // ========== OPÉRATIONS ARITHMÉTIQUES ==========

    public function add(mixed $other): static
    {
        return new static($this->value + static::from($other)->getValue());
    }

    public function subtract(mixed $other): static
    {
        return new static($this->value - static::from($other)->getValue());
    }

    public function multiply(mixed $other): static
    {
        return new static($this->value * static::from($other)->getValue());
    }

    public function divide(mixed $other): static
    {
        $otherValue = static::from($other)->getValue();
        if ($otherValue == 0) {
            throw new InvalidArgumentException('Division by zero is not allowed');
        }

        return new static($this->value / $otherValue);
    }

    public function mod(mixed $other): static
    {
        $otherValue = static::from($other)->getValue();
        if ($otherValue == 0) {
            throw new InvalidArgumentException('Modulo by zero is not allowed');
        }

        return new static(fmod($this->value, $otherValue));
    }

    public function pow(mixed $exponent): static
    {
        return new static($this->value ** static::from($exponent)->getValue());
    }

    public function increment(mixed $by = null): static
    {
        if ($by === null) {
            return new static($this->value + 1.0);
        }

        return $this->add($by);
    }

    public function decrement(mixed $by = null): static
    {
        if ($by === null) {
            return new static($this->value - 1.0);
        }

        return $this->subtract($by);
    }

    // ========== OPÉRATIONS MATHÉMATIQUES AVANCÉES ==========

    public function abs(): static
    {
        return new static(abs($this->value));
    }

    public function negate(): static
    {
        return new static(-$this->value);
    }

    public function sqrt(): static
    {
        if ($this->value < 0) {
            throw new InvalidArgumentException('Square root of negative number is not defined');
        }

        return new static(sqrt($this->value));
    }

    public function log(): static
    {
        if ($this->value <= 0) {
            throw new InvalidArgumentException('Logarithm of non-positive number is not defined');
        }

        return new static(log($this->value));
    }

    public function log10(): static
    {
        if ($this->value <= 0) {
            throw new InvalidArgumentException('Logarithm of non-positive number is not defined');
        }

        return new static(log10($this->value));
    }

    public function logBase(mixed $base): static
    {
        $baseValue = static::from($base)->getValue();
        if ($this->value <= 0 || $baseValue <= 0 || $baseValue === 1.0) {
            throw new InvalidArgumentException('Invalid logarithm parameters');
        }

        return new static(log($this->value, $baseValue));
    }

    public function exp(): static
    {
        return new static(exp($this->value));
    }

    // ========== FONCTIONS TRIGONOMÉTRIQUES ==========

    public function sin(): static
    {
        return new static(sin($this->value));
    }

    public function cos(): static
    {
        return new static(cos($this->value));
    }

    public function tan(): static
    {
        return new static(tan($this->value));
    }

    public function asin(): static
    {
        if ($this->value < -1 || $this->value > 1) {
            throw new InvalidArgumentException('Arcsin argument must be between -1 and 1');
        }

        return new static(asin($this->value));
    }

    public function acos(): static
    {
        if ($this->value < -1 || $this->value > 1) {
            throw new InvalidArgumentException('Arccos argument must be between -1 and 1');
        }

        return new static(acos($this->value));
    }

    public function atan(): static
    {
        return new static(atan($this->value));
    }

    public function atan2(mixed $x): static
    {
        return new static(atan2($this->value, static::from($x)->getValue()));
    }

    // ========== CONVERSION D'ANGLE ==========

    public function deg2rad(): static
    {
        return new static(deg2rad($this->value));
    }

    public function rad2deg(): static
    {
        return new static(rad2deg($this->value));
    }

    // ========== FONCTIONS HYPERBOLIQUES ==========

    public function sinh(): static
    {
        return new static(sinh($this->value));
    }

    public function cosh(): static
    {
        return new static(cosh($this->value));
    }

    public function tanh(): static
    {
        return new static(tanh($this->value));
    }

    // ========== ARRONDI ==========

    public function round(mixed $precision = null): static
    {
        $prec = $precision !== null ? (int) static::from($precision)->getValue() : 0;

        return new static(round($this->value, $prec));
    }

    public function ceil(): static
    {
        return new static(ceil($this->value));
    }

    public function floor(): static
    {
        return new static(floor($this->value));
    }

    // ========== FACTORIELLE ==========

    public function factorial(): static
    {
        $value = (int) $this->value;
        if ($value < 0 || $value != $this->value) {
            throw new InvalidArgumentException('Factorial is only defined for non-negative integers');
        }
        $result = 1;
        for ($i = 2; $i <= $value; $i++) {
            $result *= $i;
        }

        return new static($result);
    }

    // ========== OPÉRATIONS BIT À BIT ==========
    // Applicables uniquement si la valeur est un entier

    public function bitwiseAnd(mixed $other): static
    {
        $this->ensureInteger();
        $otherValue = (int) static::from($other)->getValue();

        return new static($this->value & $otherValue);
    }

    public function bitwiseOr(mixed $other): static
    {
        $this->ensureInteger();
        $otherValue = (int) static::from($other)->getValue();

        return new static($this->value | $otherValue);
    }

    public function bitwiseXor(mixed $other): static
    {
        $this->ensureInteger();
        $otherValue = (int) static::from($other)->getValue();

        return new static($this->value ^ $otherValue);
    }

    public function bitwiseNot(): static
    {
        $this->ensureInteger();

        return new static(~$this->value);
    }

    public function shiftLeft(mixed $positions): static
    {
        $this->ensureInteger();
        $pos = (int) static::from($positions)->getValue();
        if ($pos < 0) {
            throw new InvalidArgumentException('Shift positions cannot be negative');
        }

        return new static($this->value << $pos);
    }

    public function shiftRight(mixed $positions): static
    {
        $this->ensureInteger();
        $pos = (int) static::from($positions)->getValue();
        if ($pos < 0) {
            throw new InvalidArgumentException('Shift positions cannot be negative');
        }

        return new static($this->value >> $pos);
    }

    private function ensureInteger(): void
    {
        if ($this->value != (int) $this->value) {
            throw new InvalidArgumentException('Bitwise operations only work on integers');
        }
    }

    // ========== DIVISEURS ET FACTEURS ==========

    public function getDivisors(): ListCollection
    {
        $this->ensureInteger();
        $value = (int) abs($this->value);
        $divisors = [];
        for ($i = 1; $i <= $value; $i++) {
            if ($value % $i === 0) {
                $divisors[] = new static($i);
            }
        }

        return ListCollection::from($divisors);
    }

    public function getPrimeFactors(): ListCollection
    {
        $this->ensureInteger();
        $number = (int) abs($this->value);
        $factors = [];
        $divisor = 2;

        while ($number > 1) {
            if ($number % $divisor === 0) {
                $factors[] = new static($divisor);
                $number /= $divisor;
            } else {
                $divisor++;
            }
        }

        return ListCollection::from($factors);
    }

    public function gcd(mixed $other): static
    {
        $this->ensureInteger();
        $otherValue = (int) static::from($other)->getValue();
        $a = (int) abs($this->value);
        $b = abs($otherValue);

        while ($b !== 0) {
            $temp = $b;
            $b = $a % $b;
            $a = $temp;
        }

        return new static($a);
    }

    public function lcm(mixed $other): static
    {
        $this->ensureInteger();
        $otherValue = (int) static::from($other)->getValue();
        $a = (int) $this->value;
        if ($a === 0 || $otherValue === 0) {
            return new static(0);
        }
        $gcd = (int) $this->gcd($otherValue)->getValue();

        return new static(abs($a * $otherValue) / $gcd);
    }

    // ========== CONVERSION ==========

    public function toInt(): int
    {
        return (int) $this->value;
    }

    public function toFloat(): static
    {
        return $this;
    }

    public function toIntCeil(): int
    {
        return (int) ceil($this->value);
    }

    public function toIntFloor(): int
    {
        return (int) floor($this->value);
    }

    public function toIntRound(): int
    {
        return (int) round($this->value);
    }

    // ========== VÉRIFICATIONS ==========

    public function isZero(): BoolVO
    {
        return BoolVO::from($this->value === 0.0);
    }

    public function isPositive(): BoolVO
    {
        return BoolVO::from($this->value > 0);
    }

    public function isNegative(): BoolVO
    {
        return BoolVO::from($this->value < 0);
    }

    public function isEven(): BoolVO
    {
        $this->ensureInteger();

        return BoolVO::from((int) $this->value % 2 === 0);
    }

    public function isOdd(): BoolVO
    {
        $this->ensureInteger();

        return BoolVO::from((int) $this->value % 2 !== 0);
    }

    public function isFinite(): BoolVO
    {
        return BoolVO::from(is_finite($this->value));
    }

    public function isInfinite(): BoolVO
    {
        return BoolVO::from(is_infinite($this->value));
    }

    public function isNan(): BoolVO
    {
        return BoolVO::from(is_nan($this->value));
    }

    public function isPrime(): BoolVO
    {
        // Vérifier si c'est un entier
        if (! $this->isInteger()->getValue()) {
            return BoolVO::from(false);
        }

        $value = (int) $this->value;

        if ($value < 2) {
            return BoolVO::from(false);
        }

        if ($value === 2) {
            return BoolVO::from(true);
        }

        if ($value % 2 === 0) {
            return BoolVO::from(false);
        }

        $sqrt = (int) sqrt($value);
        for ($i = 3; $i <= $sqrt; $i += 2) {
            if ($value % $i === 0) {
                return BoolVO::from(false);
            }
        }

        return BoolVO::from(true);
    }

    public function isInteger(): BoolVO
    {
        return BoolVO::from($this->value === (float) (int) $this->value);
    }

    public function isDivisibleBy(mixed $other): BoolVO
    {
        $this->ensureInteger();
        $otherValue = (int) static::from($other)->getValue();
        if ($otherValue == 0) {
            throw new InvalidArgumentException('Division by zero is not allowed');
        }

        return BoolVO::from((int) $this->value % $otherValue === 0);
    }

    // ========== COMPARAISON ==========

    public function sign(): static
    {
        if ($this->value > 0) {
            return new static(1.0);
        }
        if ($this->value < 0) {
            return new static(-1.0);
        }

        return new static(0.0);
    }

    public function compare(mixed $other): static
    {
        $otherValue = static::from($other)->getValue();

        if ($this->value < $otherValue) {
            return new static(-1.0);
        }
        if ($this->value > $otherValue) {
            return new static(1.0);
        }

        return new static(0.0);
    }

    public function equal(mixed $other): BoolVO
    {
        return BoolVO::from($this->compare($other)->getValue() === 0.0);
    }

    public function greaterThan(mixed $other): BoolVO
    {
        return BoolVO::from($this->compare($other)->getValue() === 1.0);
    }

    public function greaterThanOrEqual(mixed $other): BoolVO
    {
        return BoolVO::from($this->compare($other)->getValue() >= 0.0);
    }

    public function lessThan(mixed $other): BoolVO
    {
        return BoolVO::from($this->compare($other)->getValue() === -1.0);
    }

    public function lessThanOrEqual(mixed $other): BoolVO
    {
        return BoolVO::from($this->compare($other)->getValue() <= 0.0);
    }

    public function min(mixed $other): static
    {
        $otherValue = static::from($other)->getValue();

        return $this->value < $otherValue ? $this : new static($otherValue);
    }

    public function max(mixed $other): static
    {
        $otherValue = static::from($other)->getValue();

        return $this->value > $otherValue ? $this : new static($otherValue);
    }

    public function clamp(mixed $min, mixed $max): static
    {
        $minValue = static::from($min)->getValue();
        $maxValue = static::from($max)->getValue();

        if ($minValue > $maxValue) {
            throw new InvalidArgumentException('Min cannot be greater than max');
        }

        if ($this->value < $minValue) {
            return new static($minValue);
        }
        if ($this->value > $maxValue) {
            return new static($maxValue);
        }

        return $this;
    }

    // ========== COMPARAISON AVEC TOLÉRANCE ==========

    public function equalsWithTolerance(mixed $other, float $tolerance = 1e-12): BoolVO
    {
        $otherValue = static::from($other)->getValue();

        return BoolVO::from(abs($this->value - $otherValue) < $tolerance);
    }

    // ========== FORMATAGE ==========

    public function format(
        mixed $decimals = null,
        mixed $decimalSeparator = null,
        mixed $thousandsSeparator = null
    ): StringVO {
        $dec = $decimals !== null ? (int) static::from($decimals)->getValue() : 0;
        $decSep = $decimalSeparator !== null ? StringVO::from($decimalSeparator)->getValue() : '.';
        $thouSep = $thousandsSeparator !== null ? StringVO::from($thousandsSeparator)->getValue() : ',';

        return StringVO::from(number_format($this->value, $dec, $decSep, $thouSep));
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }
}

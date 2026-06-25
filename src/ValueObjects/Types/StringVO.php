<?php

declare(strict_types=1);

namespace AndyDefer\PhpVo\ValueObjects\Types;

use AndyDefer\DomainStructures\Abstracts\AbstractValueObject;
use AndyDefer\DomainStructures\Utils\ListCollection;
use AndyDefer\DomainStructures\Utils\MapCollection;
use InvalidArgumentException;

/**
 * Value Object for string values.
 *
 * Encapsulates string operations.
 *
 * @author Andy Defer
 */
class StringVO extends AbstractValueObject
{
    protected string $value;

    public function __construct(string $value = '')
    {
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    // ========== OPÉRATIONS DE BASE ==========

    public function length(): int
    {
        return strlen($this->value);
    }

    public function isEmpty(): BoolVO
    {
        return BoolVO::from($this->value === '');
    }

    public function isNotEmpty(): BoolVO
    {
        return BoolVO::from($this->value !== '');
    }

    public function trim(): self
    {
        return new self(trim($this->value));
    }

    public function ltrim(): self
    {
        return new self(ltrim($this->value));
    }

    public function rtrim(): self
    {
        return new self(rtrim($this->value));
    }

    // ========== MANIPULATION ==========

    public function prepend(mixed $text): self
    {
        return new self(self::from($text)->getValue().$this->value);
    }

    public function concat(mixed $other): self
    {
        return new self($this->value.self::from($other)->getValue());
    }

    public function repeat(mixed $times): self
    {
        return new self(str_repeat($this->value, (int) FloatVO::from($times)->getValue()));
    }

    public function substring(mixed $start, mixed $length = null): self
    {
        $startValue = (int) FloatVO::from($start)->getValue();
        $len = $length !== null ? (int) FloatVO::from($length)->getValue() : null;

        return new self(substr($this->value, $startValue, $len));
    }

    public function replace(mixed $search, mixed $replace): self
    {
        return new self(str_replace(
            StringVO::from($search)->getValue(),
            StringVO::from($replace)->getValue(),
            $this->value
        ));
    }

    public function replaceAll(mixed $replacements): self
    {
        $search = [];
        $replace = [];
        foreach (MapCollection::from($replacements) as $key => $value) {
            $search[] = StringVO::from($key)->getValue();
            $replace[] = StringVO::from($value)->getValue();
        }

        return new self(str_replace($search, $replace, $this->value));
    }

    /**
     * Strip HTML and PHP tags.
     */
    public function stripTags(): self
    {
        return new self(strip_tags($this->value));
    }

    /**
     * Strip HTML and PHP tags with allowed tags.
     */
    public function stripTagsWithAllowed(string $allowedTags): self
    {
        return new self(strip_tags($this->value, $allowedTags));
    }

    // ========== FORMATAGE ==========

    public function format(mixed $replacements): self
    {
        $result = $this->value;
        foreach (MapCollection::from($replacements)->toRawArray() as $key => $value) {
            $result = str_replace('{'.$key.'}', (string) $value, $result);
        }

        return new self($result);
    }

    public function sprintf(mixed $format, mixed ...$args): self
    {
        return new self(sprintf(StringVO::from($format)->getValue(), ...$args));
    }

    // ========== CAS ==========

    public function toUpper(): self
    {
        return new self(strtoupper($this->value));
    }

    public function toLower(): self
    {
        return new self(strtolower($this->value));
    }

    public function toUpperFirst(): self
    {
        return new self(ucfirst($this->value));
    }

    public function toLowerFirst(): self
    {
        return new self(lcfirst($this->value));
    }

    public function toUpperWords(): self
    {
        return new self(ucwords($this->value));
    }

    public function toSnakeCase(): self
    {
        return new self(strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $this->value)));
    }

    public function toCamelCase(): self
    {
        return new self(lcfirst(str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $this->value)))));
    }

    // ========== RECHERCHE ==========

    public function contains(mixed $needle): BoolVO
    {
        return BoolVO::from(str_contains($this->value, StringVO::from($needle)->getValue()));
    }

    public function startsWith(mixed $needle): BoolVO
    {
        return BoolVO::from(str_starts_with($this->value, StringVO::from($needle)->getValue()));
    }

    public function endsWith(mixed $needle): BoolVO
    {
        return BoolVO::from(str_ends_with($this->value, StringVO::from($needle)->getValue()));
    }

    public function position(mixed $needle): int
    {
        $pos = strpos($this->value, StringVO::from($needle)->getValue());

        return $pos === false ? -1 : $pos;
    }

    public function lastPosition(mixed $needle): int
    {
        $pos = strrpos($this->value, StringVO::from($needle)->getValue());

        return $pos === false ? -1 : $pos;
    }

    public function countOccurrences(mixed $needle): int
    {
        return substr_count($this->value, StringVO::from($needle)->getValue());
    }

    // ========== SPLIT ==========

    public function split(mixed $separator): ListCollection
    {
        $parts = explode(StringVO::from($separator)->getValue(), $this->value);
        $result = array_map(fn ($part) => new self($part), $parts);

        return ListCollection::from($result);
    }

    public function splitByLength(mixed $length): ListCollection
    {
        $parts = str_split($this->value, (int) FloatVO::from($length)->getValue());
        $result = array_map(fn ($part) => new self($part), $parts);

        return ListCollection::from($result);
    }

    public function chars(): ListCollection
    {
        $chars = str_split($this->value);
        $result = array_map(fn ($char) => new self($char), $chars);

        return ListCollection::from($result);
    }

    public function words(): ListCollection
    {
        $words = str_word_count($this->value, 1);
        $result = array_map(fn ($word) => new self($word), $words);

        return ListCollection::from($result);
    }

    // ========== COMPARAISON ==========

    public function equal(mixed $other): BoolVO
    {
        return BoolVO::from($this->value === StringVO::from($other)->getValue());
    }

    public function equalsIgnoreCase(mixed $other): BoolVO
    {
        return BoolVO::from(strtolower($this->value) === strtolower(StringVO::from($other)->getValue()));
    }

    public function compare(mixed $other): int
    {
        return strcmp($this->value, StringVO::from($other)->getValue());
    }

    // ========== CONVERSION ==========

    public function toInt(): int
    {
        if (! is_numeric($this->value)) {
            throw new InvalidArgumentException("Cannot convert '{$this->value}' to integer");
        }

        return (int) $this->value;
    }

    public function toFloat(): FloatVO
    {
        if (! is_numeric($this->value)) {
            throw new InvalidArgumentException("Cannot convert '{$this->value}' to float");
        }

        return FloatVO::from((float) $this->value);
    }

    public function toBool(): BoolVO
    {
        return BoolVO::from(filter_var($this->value, FILTER_VALIDATE_BOOLEAN));
    }

    public function toArray(): ListCollection
    {
        return $this->split(',');
    }

    public function __toString(): string
    {
        return $this->value;
    }
}

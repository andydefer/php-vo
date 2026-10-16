<?php

declare(strict_types=1);

namespace AndyDefer\PhpVo\ValueObjects;

use AndyDefer\DomainStructures\Abstracts\AbstractValueObject;
use AndyDefer\DomainStructures\Collections\Utility\FloatTypedCollection;
use AndyDefer\PhpVo\ValueObjects\Strings\UuidVO;
use AndyDefer\PhpVo\ValueObjects\Types\FloatVO;
use AndyDefer\PhpVo\ValueObjects\Types\StringVO;
use InvalidArgumentException;

/**
 * Immutable Value Object representing a geographic coordinate pair.
 *
 * Stores exactly two floats in the order expected by LocationIQ:
 * `[longitude, latitude]`. Any other arity is rejected at construction.
 *
 * An optional UUID, a human-readable name, and a description can be attached
 * to label a specific location (e.g. a pharmacy, a stop, a warehouse).
 *
 * @example
 * $location = LocationVO::fromArray([15.3222, -4.3250]);
 * echo $location->getLongitude(); // 15.3222
 * echo $location->getLatitude();  // -4.325
 * echo (string) $location;        // "15.322200,-4.325000"
 */
final class LocationVO extends AbstractValueObject
{
    /**
     * Exact number of floats a location must contain.
     */
    private const COORDINATE_COUNT = 2;

    public function __construct(
        private readonly FloatTypedCollection $coordinates,
        private readonly ?UuidVO $uuid = null,
        private readonly ?StringVO $name = null,
        private readonly ?StringVO $description = null,
    ) {
        if ($coordinates->count() !== self::COORDINATE_COUNT) {
            throw new InvalidArgumentException(
                sprintf(
                    'Location must contain exactly %d floats, %d given.',
                    self::COORDINATE_COUNT,
                    $coordinates->count()
                )
            );
        }
    }

    /**
     * Creates a location from a raw array of numeric coordinates.
     *
     * @param  array<int, float|int|string>  $coordinates  Ordered as `[longitude, latitude]`.
     * @param  UuidVO|string|null  $uuid  Optional identifier (UuidVO, raw UUID string, or null).
     * @param  StringVO|string|null  $name  Optional name (StringVO, raw string, or null).
     * @param  StringVO|string|null  $description  Optional description (StringVO, raw string, or null).
     *
     * @throws InvalidArgumentException When a coordinate is not numeric, the arity is invalid, or the UUID string is malformed.
     */
    public static function fromArray(
        array $coordinates,
        UuidVO|string|null $uuid = null,
        StringVO|string|null $name = null,
        StringVO|string|null $description = null,
    ): self {
        $collection = new FloatTypedCollection;

        foreach ($coordinates as $coordinate) {
            if (! is_numeric($coordinate)) {
                throw new InvalidArgumentException(
                    sprintf(
                        'Location coordinate must be numeric, %s given.',
                        get_debug_type($coordinate)
                    )
                );
            }

            $collection->add((float) $coordinate);
        }

        if (is_string($uuid)) {
            $uuid = new UuidVO($uuid);
        }

        if (is_string($name)) {
            $name = StringVO::from($name);
        }

        if (is_string($description)) {
            $description = StringVO::from($description);
        }

        return new self($collection, $uuid, $name, $description);
    }

    /**
     * Converts this location to a CoordinatesVO.
     *
     * CoordinatesVO stores latitude and longitude as separate floats.
     * The optional UUID, name, and description are preserved.
     */
    public function toCoordinates(): CoordinatesVO
    {
        return new CoordinatesVO(
            FloatVO::from($this->getLatitude()),
            FloatVO::from($this->getLongitude()),
            $this->uuid,
            $this->name,
            $this->description,
        );
    }

    /**
     * Returns a new instance with the given longitude.
     */
    public function withLongitude(float $longitude): self
    {
        return self::fromArray(
            [$longitude, $this->getLatitude()],
            $this->uuid,
            $this->name,
            $this->description,
        );
    }

    /**
     * Returns a new instance with the given latitude.
     */
    public function withLatitude(float $latitude): self
    {
        return self::fromArray(
            [$this->getLongitude(), $latitude],
            $this->uuid,
            $this->name,
            $this->description,
        );
    }

    /**
     * Returns a new instance with the given UUID.
     *
     * Accepts either a UuidVO instance, a raw UUID string, or null to clear.
     *
     * @throws InvalidArgumentException When a string is not a valid UUID.
     */
    public function withUuid(UuidVO|string|null $uuid): self
    {
        if (is_string($uuid)) {
            $uuid = new UuidVO($uuid);
        }

        return new self(
            $this->coordinates,
            $uuid,
            $this->name,
            $this->description,
        );
    }

    /**
     * Returns a new instance with the given name.
     *
     * Accepts either a StringVO instance, a raw string, or null to clear.
     */
    public function withName(StringVO|string|null $name): self
    {
        if (is_string($name)) {
            $name = StringVO::from($name);
        }

        return new self(
            $this->coordinates,
            $this->uuid,
            $name,
            $this->description,
        );
    }

    /**
     * Returns a new instance with the given description.
     *
     * Accepts either a StringVO instance, a raw string, or null to clear.
     */
    public function withDescription(StringVO|string|null $description): self
    {
        if (is_string($description)) {
            $description = StringVO::from($description);
        }

        return new self(
            $this->coordinates,
            $this->uuid,
            $this->name,
            $description,
        );
    }

    /**
     * Returns the longitude (first element of the coordinate pair).
     */
    public function getLongitude(): float
    {
        return (float) $this->coordinates->first();
    }

    /**
     * Returns the latitude (second element of the coordinate pair).
     */
    public function getLatitude(): float
    {
        return (float) $this->coordinates->last();
    }

    /**
     * Returns the exact number of stored coordinates.
     */
    public function getCoordinateCount(): int
    {
        return $this->coordinates->count();
    }

    /**
     * Returns the underlying coordinate collection.
     */
    public function getValue(): FloatTypedCollection
    {
        return $this->coordinates;
    }

    /**
     * Gets the optional UUID identifying this location.
     */
    public function getUuid(): ?UuidVO
    {
        return $this->uuid;
    }

    /**
     * Gets the optional human-readable name of this location.
     */
    public function getName(): ?StringVO
    {
        return $this->name;
    }

    /**
     * Gets the optional human-readable description of this location.
     */
    public function getDescription(): ?StringVO
    {
        return $this->description;
    }

    /**
     * Whether this location has been labelled (UUID, name, or description).
     */
    public function isNamed(): bool
    {
        return $this->uuid !== null
            || $this->name !== null
            || $this->description !== null;
    }

    /**
     * Returns the coordinates as a raw scalar array.
     *
     * @return array{0: float, 1: float}
     */
    public function toArray(): array
    {
        return $this->coordinates->toArray();
    }

    /**
     * {@inheritDoc}
     */
    public function equals(AbstractValueObject $other): bool
    {
        if (! $other instanceof self) {
            return false;
        }

        if ($this->coordinates->toArray() !== $other->coordinates->toArray()) {
            return false;
        }

        if (($this->uuid === null) !== ($other->uuid === null)) {
            return false;
        }

        if ($this->uuid !== null && ! $this->uuid->equals($other->uuid)) {
            return false;
        }

        if (($this->name === null) !== ($other->name === null)) {
            return false;
        }

        if ($this->name !== null && ! $this->name->equals($other->name)) {
            return false;
        }

        if (($this->description === null) !== ($other->description === null)) {
            return false;
        }

        if ($this->description !== null && ! $this->description->equals($other->description)) {
            return false;
        }

        return true;
    }

    /**
     * Returns the location formatted as `longitude,latitude` with 6 decimals.
     */
    public function __toString(): string
    {
        return sprintf('%F,%F', $this->getLongitude(), $this->getLatitude());
    }
}

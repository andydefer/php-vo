<?php

declare(strict_types=1);

namespace AndyDefer\PhpVo\ValueObjects;

use AndyDefer\DomainStructures\Abstracts\AbstractValueObject;
use AndyDefer\DomainStructures\Collections\Utility\FloatTypedCollection;
use AndyDefer\PhpVo\ValueObjects\Abstracts\AbstractCoordinatesVO;
use AndyDefer\PhpVo\ValueObjects\Strings\UuidVO;
use AndyDefer\PhpVo\ValueObjects\Types\FloatVO;
use AndyDefer\PhpVo\ValueObjects\Types\StringVO;
use InvalidArgumentException;

final class LocationVO extends AbstractCoordinatesVO
{
    private const COORDINATE_COUNT = 2;

    public function __construct(
        private readonly FloatTypedCollection $coordinates,
        private readonly ?UuidVO $uuid = null,
        private readonly ?StringVO $name = null,
        private readonly ?StringVO $description = null,
    ) {
        if ($coordinates->count() !== self::COORDINATE_COUNT) {
            throw new InvalidArgumentException(sprintf(
                'Location must contain exactly %d floats, %d given.',
                self::COORDINATE_COUNT,
                $coordinates->count(),
            ));
        }
    }

    public static function fromArray(
        array $coordinates,
        UuidVO|string|null $uuid = null,
        StringVO|string|null $name = null,
        StringVO|string|null $description = null,
    ): self {
        $collection = new FloatTypedCollection;

        foreach ($coordinates as $coordinate) {
            if (! is_numeric($coordinate)) {
                throw new InvalidArgumentException(sprintf(
                    'Location coordinate must be numeric, %s given.',
                    get_debug_type($coordinate),
                ));
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
     * Longitude (first element) as a raw float (public API unchanged).
     */
    public function getLongitude(): float
    {
        return (float) $this->coordinates->first();
    }

    /**
     * Latitude (second element) as a raw float (public API unchanged).
     */
    public function getLatitude(): float
    {
        return (float) $this->coordinates->last();
    }

    protected function latitudeValue(): float
    {
        return (float) $this->coordinates->last();
    }

    protected function longitudeValue(): float
    {
        return (float) $this->coordinates->first();
    }

    public function getCoordinateCount(): int
    {
        return $this->coordinates->count();
    }

    public function getValue(): FloatTypedCollection
    {
        return $this->coordinates;
    }

    /**
     * @return array{0: float, 1: float}
     */
    public function toArray(): array
    {
        return $this->coordinates->toArray();
    }

    public function toCoordinates(): CoordinatesVO
    {
        return new CoordinatesVO(
            FloatVO::from($this->latitudeValue()),
            FloatVO::from($this->longitudeValue()),
            $this->getUuid(),
            $this->getName(),
            $this->getDescription(),
        );
    }

    public function withLongitude(float $longitude): self
    {
        return self::fromArray(
            [$longitude, $this->latitudeValue()],
            $this->getUuid(),
            $this->getName(),
            $this->getDescription(),
        );
    }

    public function withLatitude(float $latitude): self
    {
        return self::fromArray(
            [$this->longitudeValue(), $latitude],
            $this->getUuid(),
            $this->getName(),
            $this->getDescription(),
        );
    }

    protected function copyWithLabels(
        ?UuidVO $uuid,
        ?StringVO $name,
        ?StringVO $description,
    ): static {
        return new self(
            $this->coordinates,
            $uuid,
            $name,
            $description,
        );
    }

    public function equals(AbstractValueObject $other): bool
    {
        if (! $other instanceof self) {
            return false;
        }

        if ($this->coordinates->toArray() !== $other->coordinates->toArray()) {
            return false;
        }

        if (($this->getUuid() === null) !== ($other->getUuid() === null)) {
            return false;
        }

        if ($this->getUuid() !== null && ! $this->getUuid()->equals($other->getUuid())) {
            return false;
        }

        if (($this->getName() === null) !== ($other->getName() === null)) {
            return false;
        }

        if ($this->getName() !== null && ! $this->getName()->equals($other->getName())) {
            return false;
        }

        if (($this->getDescription() === null) !== ($other->getDescription() === null)) {
            return false;
        }

        if ($this->getDescription() !== null && ! $this->getDescription()->equals($other->getDescription())) {
            return false;
        }

        return true;
    }

    /**
     * LocationIQ order: `longitude,latitude` with 6 decimals (unchanged).
     */
    public function __toString(): string
    {
        return sprintf('%F,%F', $this->longitudeValue(), $this->latitudeValue());
    }
}

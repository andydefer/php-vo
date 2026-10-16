<?php

declare(strict_types=1);

namespace AndyDefer\PhpVo\ValueObjects;

use AndyDefer\DomainStructures\Abstracts\AbstractValueObject;
use AndyDefer\DomainStructures\Utils\StrictAssociative;
use AndyDefer\PhpVo\Enums\DistanceUnit;
use AndyDefer\PhpVo\ValueObjects\Types\FloatVO;

/**
 * Immutable Value Object representing a single leg of an itinerary.
 *
 * A leg is the segment from `from` to `to`, with its Haversine distance
 * expressed in the itinerary's unit.
 */
final class ItineraryLegVO extends AbstractValueObject
{
    public function __construct(
        private readonly int $index,
        private readonly CoordinatesVO $from,
        private readonly CoordinatesVO $to,
        private readonly FloatVO $distance,
        private readonly DistanceUnit $unit,
    ) {}

    public function getIndex(): int
    {
        return $this->index;
    }

    public function getFrom(): CoordinatesVO
    {
        return $this->from;
    }

    public function getTo(): CoordinatesVO
    {
        return $this->to;
    }

    public function getDistance(): FloatVO
    {
        return $this->distance;
    }

    public function getUnit(): DistanceUnit
    {
        return $this->unit;
    }

    /**
     * Return a new leg expressed in another distance unit.
     */
    public function withUnit(DistanceUnit $unit): self
    {
        if ($unit === $this->unit) {
            return $this;
        }

        $converted = FloatVO::from(
            $this->unit->convertTo($this->distance->getValue(), $unit),
        )->round(2);

        return new self($this->index, $this->from, $this->to, $converted, $unit);
    }

    /**
     * {@inheritDoc}
     */
    public function getValue(): StrictAssociative
    {
        return StrictAssociative::from([
            'index' => $this->index,
            'from' => $this->from,
            'to' => $this->to,
            'distance' => $this->distance,
            'unit' => $this->unit,
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function equals(AbstractValueObject $other): bool
    {
        if (! $other instanceof self) {
            return false;
        }

        return $this->index === $other->index
            && $this->unit === $other->unit
            && $this->distance->equals($other->distance)
            && $this->from->equals($other->from)
            && $this->to->equals($other->to);
    }
}

<?php

declare(strict_types=1);

namespace AndyDefer\PhpVo\ValueObjects;

use AndyDefer\DomainStructures\Abstracts\AbstractValueObject;
use AndyDefer\DomainStructures\Utils\Associative;
use AndyDefer\PhpVo\Enums\DistanceUnit;
use AndyDefer\PhpVo\ValueObjects\Types\FloatVO;

/**
 * Immutable Value Object aggregating metrics for a complete itinerary.
 */
final class ItinerarySummaryVO extends AbstractValueObject
{
    public function __construct(
        private readonly int $legCount,
        private readonly int $pointCount,
        private readonly int $intermediateCount,
        private readonly FloatVO $totalDistance,
        private readonly FloatVO $averageLegDistance,
        private readonly FloatVO $longestLegDistance,
        private readonly int $longestLegIndex,
        private readonly FloatVO $shortestLegDistance,
        private readonly int $shortestLegIndex,
        private readonly DistanceUnit $unit,
    ) {}

    public function getLegCount(): int
    {
        return $this->legCount;
    }

    public function getPointCount(): int
    {
        return $this->pointCount;
    }

    public function getIntermediateCount(): int
    {
        return $this->intermediateCount;
    }

    public function getTotalDistance(): FloatVO
    {
        return $this->totalDistance;
    }

    public function getAverageLegDistance(): FloatVO
    {
        return $this->averageLegDistance;
    }

    public function getLongestLegDistance(): FloatVO
    {
        return $this->longestLegDistance;
    }

    public function getLongestLegIndex(): int
    {
        return $this->longestLegIndex;
    }

    public function getShortestLegDistance(): FloatVO
    {
        return $this->shortestLegDistance;
    }

    public function getShortestLegIndex(): int
    {
        return $this->shortestLegIndex;
    }

    public function getUnit(): DistanceUnit
    {
        return $this->unit;
    }

    /**
     * Return a new summary expressed in another distance unit.
     */
    public function withUnit(DistanceUnit $unit): self
    {
        if ($unit === $this->unit) {
            return $this;
        }

        $convert = fn (FloatVO $value): FloatVO => FloatVO::from(
            $this->unit->convertTo($value->getValue(), $unit),
        )->round(2);

        return new self(
            legCount: $this->legCount,
            pointCount: $this->pointCount,
            intermediateCount: $this->intermediateCount,
            totalDistance: $convert($this->totalDistance),
            averageLegDistance: $convert($this->averageLegDistance),
            longestLegDistance: $convert($this->longestLegDistance),
            longestLegIndex: $this->longestLegIndex,
            shortestLegDistance: $convert($this->shortestLegDistance),
            shortestLegIndex: $this->shortestLegIndex,
            unit: $unit,
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getValue(): Associative
    {
        return Associative::from([
            'legCount' => $this->legCount,
            'pointCount' => $this->pointCount,
            'intermediateCount' => $this->intermediateCount,
            'totalDistance' => $this->totalDistance,
            'averageLegDistance' => $this->averageLegDistance,
            'longestLegDistance' => $this->longestLegDistance,
            'longestLegIndex' => $this->longestLegIndex,
            'shortestLegDistance' => $this->shortestLegDistance,
            'shortestLegIndex' => $this->shortestLegIndex,
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

        return $this->legCount === $other->legCount
            && $this->pointCount === $other->pointCount
            && $this->intermediateCount === $other->intermediateCount
            && $this->longestLegIndex === $other->longestLegIndex
            && $this->shortestLegIndex === $other->shortestLegIndex
            && $this->unit === $other->unit
            && $this->totalDistance->equals($other->totalDistance)
            && $this->averageLegDistance->equals($other->averageLegDistance)
            && $this->longestLegDistance->equals($other->longestLegDistance)
            && $this->shortestLegDistance->equals($other->shortestLegDistance);
    }
}

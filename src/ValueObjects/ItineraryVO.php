<?php

declare(strict_types=1);

namespace AndyDefer\PhpVo\ValueObjects;

use AndyDefer\DomainStructures\Abstracts\AbstractValueObject;
use AndyDefer\DomainStructures\Utils\Associative as StrictAssociative;
use AndyDefer\PhpVo\Collections\CoordinatesVOCollection;
use AndyDefer\PhpVo\Collections\ItineraryLegVOCollection;
use AndyDefer\PhpVo\Enums\DistanceUnit;
use AndyDefer\PhpVo\Enums\ItineraryAlgorithm;
use AndyDefer\PhpVo\ValueObjects\Types\FloatVO;
use InvalidArgumentException;

/**
 * Immutable Value Object representing an ordered itinerary between a start
 * point and an end point, passing through a set of intermediate points.
 *
 * The itinerary is automatically reordered using the selected algorithm so
 * that the total Haversine distance is minimized.
 *
 * Available algorithms:
 *   - GREEDY       : nearest-neighbor greedy. Approximate. O(n²).
 *   - BRUTE_FORCE  : exhaustive permutation search. Exact. O(n!). n ≤ 8.
 *   - HELD_KARP    : dynamic programming. Exact. O(n² · 2ⁿ). n ≤ 15.
 *   - TWO_OPT      : greedy + 2-opt local search. Approximate. n ≤ 30.
 *
 * Each algorithm refuses to run above its practical limit; an
 * InvalidArgumentException is thrown to prevent hanging or memory exhaustion.
 *
 * All distances are computed with the Haversine formula; no external service
 * (LocationIQ, Google Maps, ...) is involved.
 *
 * The number of intermediate points is limited to {@see self::MAX_INTERMEDIATES}.
 */
final class ItineraryVO extends AbstractValueObject
{
    public const MAX_INTERMEDIATES = 30;

    private const EARTH_RADIUS_KM = 6371.0;

    private const DEGREES_TO_RADIANS = M_PI / 180;

    public function __construct(
        private readonly CoordinatesVO $start,
        private readonly CoordinatesVOCollection $intermediates,
        private readonly CoordinatesVO $end,
        private readonly DistanceUnit $unit = DistanceUnit::KILOMETRE,
        private readonly ItineraryAlgorithm $algorithm = ItineraryAlgorithm::GREEDY,
    ) {
        $count = $intermediates->count();

        if ($count > self::MAX_INTERMEDIATES) {
            throw new InvalidArgumentException(sprintf(
                'Itinerary cannot contain more than %d intermediate points, %d given.',
                self::MAX_INTERMEDIATES,
                $count,
            ));
        }

        if (! $algorithm->supports($count)) {
            throw new InvalidArgumentException(sprintf(
                'Algorithm "%s" supports at most %d intermediate points, %d given. '
                .'Use ItineraryAlgorithm::recommendedFor() or a lighter algorithm.',
                $algorithm->value,
                $algorithm->maxIntermediates(),
                $count,
            ));
        }
    }

    public function withUnit(DistanceUnit $unit): self
    {
        return new self(
            $this->start,
            $this->intermediates,
            $this->end,
            $unit,
            $this->algorithm,
        );
    }

    /**
     * Return a new itinerary using the specified algorithm.
     *
     * @throws InvalidArgumentException If the current intermediate count exceeds
     *                                  the target algorithm's practical limit.
     */
    public function withAlgorithm(ItineraryAlgorithm $algorithm): self
    {
        return new self(
            $this->start,
            $this->intermediates,
            $this->end,
            $this->unit,
            $algorithm,
        );
    }

    /**
     * Return a new itinerary using the algorithm recommended for its size.
     */
    public function withBestAlgorithm(): self
    {
        return $this->withAlgorithm(
            ItineraryAlgorithm::recommendedFor($this->intermediates->count()),
        );
    }

    public function getStart(): CoordinatesVO
    {
        return $this->start;
    }

    public function getEnd(): CoordinatesVO
    {
        return $this->end;
    }

    public function getIntermediates(): CoordinatesVOCollection
    {
        return $this->intermediates;
    }

    public function getUnit(): DistanceUnit
    {
        return $this->unit;
    }

    public function getAlgorithm(): ItineraryAlgorithm
    {
        return $this->algorithm;
    }

    /**
     * The ordered path (start → intermediates in optimal order → end).
     */
    public function getOrderedPath(): CoordinatesVOCollection
    {
        $remaining = [];
        foreach ($this->intermediates as $point) {
            $remaining[] = $point;
        }

        $ordered = match ($this->algorithm) {
            ItineraryAlgorithm::GREEDY => $this->orderByGreedy($remaining),
            ItineraryAlgorithm::BRUTE_FORCE => $this->orderByBruteForce($remaining),
            ItineraryAlgorithm::HELD_KARP => $this->orderByHeldKarp($remaining),
            ItineraryAlgorithm::TWO_OPT => $this->orderByTwoOpt(
                $this->orderByGreedy($remaining),
            ),
        };

        $path = new CoordinatesVOCollection;
        $path->add($this->start);

        foreach ($ordered as $point) {
            $path->add($point);
        }

        $path->add($this->end);

        return $path;
    }

    /**
     * All legs (segments) of the ordered itinerary.
     */
    public function legs(): ItineraryLegVOCollection
    {
        $path = $this->getOrderedPath();

        $legs = new ItineraryLegVOCollection;

        $previous = null;
        $index = 0;

        foreach ($path as $coordinates) {
            if ($previous !== null) {
                $legs->add(new ItineraryLegVO(
                    index: $index,
                    from: $previous,
                    to: $coordinates,
                    distance: FloatVO::from(
                        $this->distanceBetween($previous, $coordinates),
                    ),
                    unit: $this->unit,
                ));

                $index++;
            }

            $previous = $coordinates;
        }

        return $legs;
    }

    public function totalDistance(): FloatVO
    {
        $total = 0.0;

        foreach ($this->legs() as $leg) {
            $total += $leg->getDistance()->getValue();
        }

        return FloatVO::from($total)->round(2);
    }

    public function summary(): ItinerarySummaryVO
    {
        $legs = $this->legs();

        $pointCount = 2 + $this->intermediates->count();
        $intermediateCount = $this->intermediates->count();

        if ($legs->isEmpty()) {
            return new ItinerarySummaryVO(
                legCount: 0,
                pointCount: $pointCount,
                intermediateCount: $intermediateCount,
                totalDistance: FloatVO::from(0.0),
                averageLegDistance: FloatVO::from(0.0),
                longestLegDistance: FloatVO::from(0.0),
                longestLegIndex: -1,
                shortestLegDistance: FloatVO::from(0.0),
                shortestLegIndex: -1,
                unit: $this->unit,
            );
        }

        $total = 0.0;
        $longest = $legs->first();
        $shortest = $legs->first();

        foreach ($legs as $leg) {
            $distance = $leg->getDistance()->getValue();
            $total += $distance;

            if ($distance > $longest->getDistance()->getValue()) {
                $longest = $leg;
            }

            if ($distance < $shortest->getDistance()->getValue()) {
                $shortest = $leg;
            }
        }

        return new ItinerarySummaryVO(
            legCount: $legs->count(),
            pointCount: $pointCount,
            intermediateCount: $intermediateCount,
            totalDistance: FloatVO::from(round($total, 2)),
            averageLegDistance: FloatVO::from(round($total / $legs->count(), 2)),
            longestLegDistance: $longest->getDistance(),
            longestLegIndex: $longest->getIndex(),
            shortestLegDistance: $shortest->getDistance(),
            shortestLegIndex: $shortest->getIndex(),
            unit: $this->unit,
        );
    }

    /**
     * The distance between two arbitrary coordinates, in this itinerary's unit.
     */
    public function distanceBetween(CoordinatesVO $from, CoordinatesVO $to): float
    {
        $centralAngle = $this->centralAngle($from, $to);

        // Earth radius is defined in kilometres; convert it to the itinerary's unit.
        $radiusInTargetUnit = self::EARTH_RADIUS_KM
            * (DistanceUnit::KILOMETRE->toMetres() / $this->unit->toMetres());

        return round($centralAngle * $radiusInTargetUnit, 2);
    }

    /**
     * {@inheritDoc}
     *
     * Returns the strict input structure of the itinerary:
     *   - `start`          → CoordinatesVO
     *   - `intermediates`  → CoordinatesVOCollection
     *   - `end`            → CoordinatesVO
     *   - `unit`           → DistanceUnit
     *   - `algorithm`      → ItineraryAlgorithm
     */
    public function getValue(): StrictAssociative
    {
        return StrictAssociative::from([
            'start' => $this->start,
            'intermediates' => $this->intermediates,
            'end' => $this->end,
            'unit' => $this->unit,
            'algorithm' => $this->algorithm,
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

        if ($this->unit !== $other->unit) {
            return false;
        }

        if ($this->algorithm !== $other->algorithm) {
            return false;
        }

        if (! $this->start->equals($other->start)) {
            return false;
        }

        if (! $this->end->equals($other->end)) {
            return false;
        }

        $a = $this->getOrderedPath()->toArray();
        $b = $other->getOrderedPath()->toArray();

        if (count($a) !== count($b)) {
            return false;
        }

        foreach ($a as $index => $coordinates) {
            if (! $coordinates->equals($b[$index])) {
                return false;
            }
        }

        return true;
    }

    // ============================================================
    // ALGORITHMS
    // ============================================================

    /**
     * Order intermediates using a greedy nearest-neighbor heuristic.
     *
     * @param  array<int, CoordinatesVO>  $intermediates
     * @return array<int, CoordinatesVO>
     */
    private function orderByGreedy(array $intermediates): array
    {
        $remaining = array_values($intermediates);
        $ordered = [];

        $current = $this->start;

        while ($remaining !== []) {
            $nearestIndex = $this->findNearestIndex($current, $remaining);
            $nearest = $remaining[$nearestIndex];

            $ordered[] = $nearest;

            unset($remaining[$nearestIndex]);
            $remaining = array_values($remaining);

            $current = $nearest;
        }

        return $ordered;
    }

    /**
     * Order intermediates by exhaustive permutation search.
     *
     * @param  array<int, CoordinatesVO>  $intermediates
     * @return array<int, CoordinatesVO>
     */
    private function orderByBruteForce(array $intermediates): array
    {
        if ($intermediates === []) {
            return [];
        }

        $bestOrder = null;
        $bestCost = null;

        $count = count($intermediates);
        $indices = range(0, $count - 1);

        foreach ($this->permutations($indices) as $permutation) {
            $order = [];
            foreach ($permutation as $index) {
                $order[] = $intermediates[$index];
            }

            $cost = $this->pathCost($order);

            if ($bestCost === null || $cost < $bestCost) {
                $bestCost = $cost;
                $bestOrder = $order;
            }
        }

        return $bestOrder ?? $intermediates;
    }

    /**
     * Order intermediates using the Held-Karp dynamic programming algorithm.
     *
     * Computes the exact optimal path for n ≤ 15 in O(n² · 2ⁿ).
     *
     * @param  array<int, CoordinatesVO>  $intermediates
     * @return array<int, CoordinatesVO>
     */
    private function orderByHeldKarp(array $intermediates): array
    {
        $n = count($intermediates);

        if ($n === 0) {
            return [];
        }

        $startIndex = $n;
        $nodes = array_merge($intermediates, [$this->start]);

        $dist = [];
        for ($i = 0; $i <= $n; $i++) {
            for ($j = 0; $j <= $n; $j++) {
                $dist[$i][$j] = $i === $j
                    ? 0.0
                    : $this->distanceBetween($nodes[$i], $nodes[$j]);
            }
        }

        $fullMask = (1 << $n) - 1;

        $dp = [];
        $parent = [];

        for ($i = 0; $i < $n; $i++) {
            $mask = 1 << $i;
            $dp[$mask][$i] = $dist[$startIndex][$i];
            $parent[$mask][$i] = $startIndex;
        }

        for ($mask = 1; $mask <= $fullMask; $mask++) {
            if (! isset($dp[$mask])) {
                continue;
            }

            for ($last = 0; $last < $n; $last++) {
                if (($mask & (1 << $last)) === 0) {
                    continue;
                }

                if (! isset($dp[$mask][$last])) {
                    continue;
                }

                $currentCost = $dp[$mask][$last];

                for ($next = 0; $next < $n; $next++) {
                    if ($mask & (1 << $next)) {
                        continue;
                    }

                    $nextMask = $mask | (1 << $next);
                    $nextCost = $currentCost + $dist[$last][$next];

                    if (! isset($dp[$nextMask][$next]) || $nextCost < $dp[$nextMask][$next]) {
                        $dp[$nextMask][$next] = $nextCost;
                        $parent[$nextMask][$next] = $last;
                    }
                }
            }
        }

        $bestCost = null;
        $bestLast = null;

        for ($last = 0; $last < $n; $last++) {
            $cost = $dp[$fullMask][$last] + $this->distanceBetween($intermediates[$last], $this->end);

            if ($bestCost === null || $cost < $bestCost) {
                $bestCost = $cost;
                $bestLast = $last;
            }
        }

        $orderedIndices = [];
        $mask = $fullMask;
        $last = $bestLast;

        while ($last !== $startIndex) {
            $orderedIndices[] = $last;
            $previous = $parent[$mask][$last] ?? $startIndex;
            $mask ^= 1 << $last;
            $last = $previous;
        }

        $orderedIndices = array_reverse($orderedIndices);

        $ordered = [];
        foreach ($orderedIndices as $index) {
            $ordered[] = $intermediates[$index];
        }

        return $ordered;
    }

    /**
     * Improve a path using the 2-opt local search.
     *
     * @param  array<int, CoordinatesVO>  $path
     * @return array<int, CoordinatesVO>
     */
    private function orderByTwoOpt(array $path): array
    {
        $n = count($path);

        if ($n < 2) {
            return $path;
        }

        $improved = true;

        while ($improved) {
            $improved = false;

            for ($i = 0; $i < $n - 1; $i++) {
                for ($j = $i + 1; $j < $n; $j++) {
                    $current = $this->pathCostBetween($path, $i, $j);
                    $candidate = $this->swappedCostBetween($path, $i, $j);

                    if ($candidate < $current) {
                        $path = $this->swapSegment($path, $i, $j);
                        $improved = true;
                    }
                }
            }
        }

        return $path;
    }

    // ============================================================
    // ALGORITHM HELPERS
    // ============================================================

    /**
     * Compute the total cost of an ordered path (start → path → end).
     *
     * @param  array<int, CoordinatesVO>  $path
     */
    private function pathCost(array $path): float
    {
        if ($path === []) {
            return $this->distanceBetween($this->start, $this->end);
        }

        $cost = 0.0;
        $previous = $this->start;

        foreach ($path as $point) {
            $cost += $this->distanceBetween($previous, $point);
            $previous = $point;
        }

        $cost += $this->distanceBetween($previous, $this->end);

        return $cost;
    }

    /**
     * Cost of the edges affected by a 2-opt move between indices $i and $j.
     *
     * @param  array<int, CoordinatesVO>  $path
     */
    private function pathCostBetween(array $path, int $i, int $j): float
    {
        $before = $i === 0 ? $this->start : $path[$i - 1];
        $after = $j === count($path) - 1 ? $this->end : $path[$j + 1];

        return $this->distanceBetween($before, $path[$i])
            + $this->distanceBetween($path[$j], $after);
    }

    /**
     * Cost of the same edges after reversing the segment [$i, $j].
     *
     * @param  array<int, CoordinatesVO>  $path
     */
    private function swappedCostBetween(array $path, int $i, int $j): float
    {
        $before = $i === 0 ? $this->start : $path[$i - 1];
        $after = $j === count($path) - 1 ? $this->end : $path[$j + 1];

        return $this->distanceBetween($before, $path[$j])
            + $this->distanceBetween($path[$i], $after);
    }

    /**
     * Return a new path with the segment between indices $i and $j reversed.
     *
     * @param  array<int, CoordinatesVO>  $path
     * @return array<int, CoordinatesVO>
     */
    private function swapSegment(array $path, int $i, int $j): array
    {
        $segment = array_slice($path, $i, $j - $i + 1);
        $segment = array_reverse($segment);

        return array_merge(
            array_slice($path, 0, $i),
            $segment,
            array_slice($path, $j + 1),
        );
    }

    /**
     * Generate all permutations of a given list of integers.
     *
     * @param  array<int, int>  $items
     * @return \Generator<int, array<int, int>>
     */
    private function permutations(array $items): \Generator
    {
        if (count($items) <= 1) {
            yield $items;

            return;
        }

        foreach ($items as $key => $item) {
            $rest = $items;
            unset($rest[$key]);

            foreach ($this->permutations(array_values($rest)) as $sub) {
                yield array_merge([$item], $sub);
            }
        }
    }

    /**
     * Find the index of the nearest point from a reference within candidates.
     *
     * @param  array<int, CoordinatesVO>  $candidates
     */
    private function findNearestIndex(CoordinatesVO $reference, array $candidates): int
    {
        $nearestIndex = 0;
        $nearestDistance = null;

        foreach ($candidates as $index => $candidate) {
            $distance = $this->distanceBetween($reference, $candidate);

            if ($nearestDistance === null || $distance < $nearestDistance) {
                $nearestDistance = $distance;
                $nearestIndex = $index;
            }
        }

        return $nearestIndex;
    }

    /**
     * Haversine central angle between two coordinates.
     */
    private function centralAngle(CoordinatesVO $from, CoordinatesVO $to): float
    {
        $latFrom = $from->getLatitude()->getValue() * self::DEGREES_TO_RADIANS;
        $latTo = $to->getLatitude()->getValue() * self::DEGREES_TO_RADIANS;
        $lonFrom = $from->getLongitude()->getValue() * self::DEGREES_TO_RADIANS;
        $lonTo = $to->getLongitude()->getValue() * self::DEGREES_TO_RADIANS;

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $a = sin($latDelta / 2) ** 2
            + cos($latFrom) * cos($latTo) * sin($lonDelta / 2) ** 2;

        $a = max(0.0, min(1.0, $a));

        return 2 * atan2(sqrt($a), sqrt(1 - $a));
    }
}

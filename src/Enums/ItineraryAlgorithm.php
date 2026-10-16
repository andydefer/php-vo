<?php

declare(strict_types=1);

namespace AndyDefer\PhpVo\Enums;

/**
 * Algorithm used to reorder the intermediate points of an itinerary.
 *
 * The choice trades off optimality against complexity.
 *
 * Each algorithm has a practical limit beyond which it either becomes too
 * slow or consumes too much memory. Calling it above its limit raises an
 * InvalidArgumentException to prevent the process from hanging or crashing.
 */
enum ItineraryAlgorithm: string
{
    /** Nearest-neighbor greedy heuristic. O(n²). Approximate. */
    case GREEDY = 'greedy';

    /** Brute-force enumeration of permutations. O(n!). Exact. n ≤ 8. */
    case BRUTE_FORCE = 'brute_force';

    /** Held-Karp dynamic programming. O(n² · 2ⁿ). Exact. n ≤ 15. */
    case HELD_KARP = 'held_karp';

    /** Greedy followed by 2-opt local search. O(n²) per pass. Approximate. */
    case TWO_OPT = 'two_opt';

    /**
     * Maximum number of intermediate points this algorithm accepts.
     */
    public function maxIntermediates(): int
    {
        return match ($this) {
            self::GREEDY => 30,
            self::BRUTE_FORCE => 8,
            self::HELD_KARP => 15,
            self::TWO_OPT => 30,
        };
    }

    /**
     * Whether this algorithm returns a provably optimal path.
     */
    public function isExact(): bool
    {
        return match ($this) {
            self::BRUTE_FORCE, self::HELD_KARP => true,
            self::GREEDY, self::TWO_OPT => false,
        };
    }

    /**
     * Whether this algorithm accepts the given number of intermediate points.
     */
    public function supports(int $intermediateCount): bool
    {
        return $intermediateCount <= $this->maxIntermediates();
    }

    /**
     * Return the recommended algorithm for a given number of intermediate points.
     *
     *  ≤ 8  → BRUTE_FORCE (exact, fast)
     *  ≤ 15 → HELD_KARP   (exact, bounded memory)
     *  > 15 → TWO_OPT     (approximate, always fast)
     */
    public static function recommendedFor(int $intermediateCount): self
    {
        return match (true) {
            $intermediateCount <= self::BRUTE_FORCE->maxIntermediates() => self::BRUTE_FORCE,
            $intermediateCount <= self::HELD_KARP->maxIntermediates() => self::HELD_KARP,
            default => self::TWO_OPT,
        };
    }
}

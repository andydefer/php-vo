<?php

declare(strict_types=1);

namespace AndyDefer\PhpVo\Tests\Unit\ValueObjects;

use AndyDefer\DomainStructures\Utils\Associative;
use AndyDefer\PhpVo\Enums\DistanceUnit;
use AndyDefer\PhpVo\ValueObjects\CoordinatesVO;
use AndyDefer\PhpVo\ValueObjects\ItinerarySummaryVO;
use AndyDefer\PhpVo\ValueObjects\Types\FloatVO;
use PHPUnit\Framework\TestCase;

final class ItinerarySummaryVOTest extends TestCase
{
    private function summary(
        int $legCount = 3,
        int $pointCount = 4,
        int $intermediateCount = 2,
        float $totalDistance = 1000.0,
        float $averageLegDistance = 333.33,
        float $longestLegDistance = 500.0,
        int $longestLegIndex = 1,
        float $shortestLegDistance = 100.0,
        int $shortestLegIndex = 2,
        DistanceUnit $unit = DistanceUnit::KILOMETRE,
    ): ItinerarySummaryVO {
        return new ItinerarySummaryVO(
            legCount: $legCount,
            pointCount: $pointCount,
            intermediateCount: $intermediateCount,
            totalDistance: FloatVO::from($totalDistance),
            averageLegDistance: FloatVO::from($averageLegDistance),
            longestLegDistance: FloatVO::from($longestLegDistance),
            longestLegIndex: $longestLegIndex,
            shortestLegDistance: FloatVO::from($shortestLegDistance),
            shortestLegIndex: $shortestLegIndex,
            unit: $unit,
        );
    }

    // ========== CONSTRUCTION & GETTERS ==========

    public function test_create_summary_with_valid_data(): void
    {
        $summary = $this->summary();

        $this->assertSame(3, $summary->getLegCount());
        $this->assertSame(4, $summary->getPointCount());
        $this->assertSame(2, $summary->getIntermediateCount());
        $this->assertSame(1000.0, $summary->getTotalDistance()->getValue());
        $this->assertSame(333.33, $summary->getAverageLegDistance()->getValue());
        $this->assertSame(500.0, $summary->getLongestLegDistance()->getValue());
        $this->assertSame(1, $summary->getLongestLegIndex());
        $this->assertSame(100.0, $summary->getShortestLegDistance()->getValue());
        $this->assertSame(2, $summary->getShortestLegIndex());
        $this->assertSame(DistanceUnit::KILOMETRE, $summary->getUnit());
    }

    public function test_getters_return_float_vo(): void
    {
        $summary = $this->summary();

        $this->assertInstanceOf(FloatVO::class, $summary->getTotalDistance());
        $this->assertInstanceOf(FloatVO::class, $summary->getAverageLegDistance());
        $this->assertInstanceOf(FloatVO::class, $summary->getLongestLegDistance());
        $this->assertInstanceOf(FloatVO::class, $summary->getShortestLegDistance());
    }

    // ========== WITH UNIT ==========

    public function test_with_unit_returns_same_instance_when_unit_unchanged(): void
    {
        $summary = $this->summary(unit: DistanceUnit::KILOMETRE);

        $same = $summary->withUnit(DistanceUnit::KILOMETRE);

        $this->assertSame($summary, $same);
    }

    public function test_with_unit_converts_every_distance(): void
    {
        $summary = $this->summary(
            totalDistance: 10.0,
            averageLegDistance: 5.0,
            longestLegDistance: 8.0,
            shortestLegDistance: 2.0,
            unit: DistanceUnit::KILOMETRE,
        );

        $converted = $summary->withUnit(DistanceUnit::METRE);

        $this->assertNotSame($summary, $converted);
        $this->assertSame(DistanceUnit::METRE, $converted->getUnit());
        $this->assertSame(10000.0, $converted->getTotalDistance()->getValue());
        $this->assertSame(5000.0, $converted->getAverageLegDistance()->getValue());
        $this->assertSame(8000.0, $converted->getLongestLegDistance()->getValue());
        $this->assertSame(2000.0, $converted->getShortestLegDistance()->getValue());
    }

    public function test_with_unit_preserves_indices_and_counts(): void
    {
        $summary = $this->summary(
            legCount: 5,
            pointCount: 6,
            intermediateCount: 4,
            longestLegIndex: 3,
            shortestLegIndex: 0,
        );

        $converted = $summary->withUnit(DistanceUnit::METRE);

        $this->assertSame(5, $converted->getLegCount());
        $this->assertSame(6, $converted->getPointCount());
        $this->assertSame(4, $converted->getIntermediateCount());
        $this->assertSame(3, $converted->getLongestLegIndex());
        $this->assertSame(0, $converted->getShortestLegIndex());
    }

    public function test_with_unit_does_not_mutate_original(): void
    {
        $summary = $this->summary(totalDistance: 10.0, unit: DistanceUnit::KILOMETRE);

        $summary->withUnit(DistanceUnit::METRE);

        $this->assertSame(DistanceUnit::KILOMETRE, $summary->getUnit());
        $this->assertSame(10.0, $summary->getTotalDistance()->getValue());
    }

    // ========== GET VALUE ==========

    public function test_get_value_returns_strict_associative(): void
    {
        $summary = $this->summary();

        $value = $summary->getValue();

        $this->assertInstanceOf(Associative::class, $value);
        $this->assertSame(3, $value->legCount);
        $this->assertSame(4, $value->pointCount);
        $this->assertSame(2, $value->intermediateCount);
        $this->assertInstanceOf(FloatVO::class, $value->totalDistance);
        $this->assertInstanceOf(FloatVO::class, $value->averageLegDistance);
        $this->assertInstanceOf(FloatVO::class, $value->longestLegDistance);
        $this->assertSame(1, $value->longestLegIndex);
        $this->assertInstanceOf(FloatVO::class, $value->shortestLegDistance);
        $this->assertSame(2, $value->shortestLegIndex);
        $this->assertSame(DistanceUnit::KILOMETRE, $value->unit);
    }

    public function test_get_value_reflects_original_inputs(): void
    {
        $summary = $this->summary(legCount: 9, totalDistance: 1234.56);

        $value = $summary->getValue();

        $this->assertSame(9, $value->legCount);
        $this->assertSame(1234.56, $value->totalDistance->getValue());
    }

    // ========== EQUALS ==========

    public function test_equals_returns_true_for_identical_summaries(): void
    {
        $a = $this->summary();
        $b = $this->summary();

        $this->assertTrue($a->equals($b));
    }

    public function test_equals_returns_false_for_different_leg_count(): void
    {
        $a = $this->summary(legCount: 3);
        $b = $this->summary(legCount: 4);

        $this->assertFalse($a->equals($b));
    }

    public function test_equals_returns_false_for_different_point_count(): void
    {
        $a = $this->summary(pointCount: 4);
        $b = $this->summary(pointCount: 5);

        $this->assertFalse($a->equals($b));
    }

    public function test_equals_returns_false_for_different_intermediate_count(): void
    {
        $a = $this->summary(intermediateCount: 2);
        $b = $this->summary(intermediateCount: 3);

        $this->assertFalse($a->equals($b));
    }

    public function test_equals_returns_false_for_different_total_distance(): void
    {
        $a = $this->summary(totalDistance: 100.0);
        $b = $this->summary(totalDistance: 200.0);

        $this->assertFalse($a->equals($b));
    }

    public function test_equals_returns_false_for_different_longest_index(): void
    {
        $a = $this->summary(longestLegIndex: 1);
        $b = $this->summary(longestLegIndex: 2);

        $this->assertFalse($a->equals($b));
    }

    public function test_equals_returns_false_for_different_unit(): void
    {
        $a = $this->summary(unit: DistanceUnit::KILOMETRE);
        $b = $this->summary(unit: DistanceUnit::METRE);

        $this->assertFalse($a->equals($b));
    }

    public function test_equals_returns_false_for_different_vo_type(): void
    {
        $summary = $this->summary();

        $this->assertFalse($summary->equals(
            CoordinatesVO::from(['latitude' => 0.0, 'longitude' => 0.0]),
        ));
    }
}

<?php

declare(strict_types=1);

namespace AndyDefer\PhpVo\Tests\Unit\ValueObjects;

use AndyDefer\DomainStructures\Utils\Associative;
use AndyDefer\PhpVo\Enums\DistanceUnit;
use AndyDefer\PhpVo\ValueObjects\CoordinatesVO;
use AndyDefer\PhpVo\ValueObjects\ItineraryLegVO;
use AndyDefer\PhpVo\ValueObjects\Types\FloatVO;
use PHPUnit\Framework\TestCase;

final class ItineraryLegVOTest extends TestCase
{
    private const PARIS = ['latitude' => 48.8566, 'longitude' => 2.3522];

    private const LONDON = ['latitude' => 51.5074, 'longitude' => -0.1278];

    private function paris(): CoordinatesVO
    {
        return CoordinatesVO::from(self::PARIS);
    }

    private function london(): CoordinatesVO
    {
        return CoordinatesVO::from(self::LONDON);
    }

    private function leg(
        int $index = 0,
        ?CoordinatesVO $from = null,
        ?CoordinatesVO $to = null,
        float $distance = 343.5,
        DistanceUnit $unit = DistanceUnit::KILOMETRE,
    ): ItineraryLegVO {
        return new ItineraryLegVO(
            index: $index,
            from: $from ?? $this->paris(),
            to: $to ?? $this->london(),
            distance: FloatVO::from($distance),
            unit: $unit,
        );
    }

    // ========== CONSTRUCTION & GETTERS ==========

    public function test_create_leg_with_valid_data(): void
    {
        $leg = $this->leg();

        $this->assertSame(0, $leg->getIndex());
        $this->assertTrue($leg->getFrom()->equals($this->paris()));
        $this->assertTrue($leg->getTo()->equals($this->london()));
        $this->assertSame(343.5, $leg->getDistance()->getValue());
        $this->assertSame(DistanceUnit::KILOMETRE, $leg->getUnit());
    }

    public function test_get_distance_returns_float_vo(): void
    {
        $leg = $this->leg();

        $this->assertInstanceOf(FloatVO::class, $leg->getDistance());
    }

    // ========== WITH UNIT ==========

    public function test_with_unit_returns_same_instance_when_unit_unchanged(): void
    {
        $leg = $this->leg(unit: DistanceUnit::KILOMETRE);

        $same = $leg->withUnit(DistanceUnit::KILOMETRE);

        $this->assertSame($leg, $same);
    }

    public function test_with_unit_converts_distance_from_km_to_m(): void
    {
        $leg = $this->leg(distance: 343.5, unit: DistanceUnit::KILOMETRE);

        $converted = $leg->withUnit(DistanceUnit::METRE);

        $this->assertNotSame($leg, $converted);
        $this->assertSame(DistanceUnit::METRE, $converted->getUnit());
        $this->assertSame(343500.0, $converted->getDistance()->getValue());
    }

    public function test_with_unit_converts_distance_from_m_to_km(): void
    {
        $leg = $this->leg(distance: 343500.0, unit: DistanceUnit::METRE);

        $converted = $leg->withUnit(DistanceUnit::KILOMETRE);

        $this->assertSame(DistanceUnit::KILOMETRE, $converted->getUnit());
        $this->assertSame(343.5, $converted->getDistance()->getValue());
    }

    public function test_with_unit_preserves_index_from_and_to(): void
    {
        $leg = $this->leg(index: 7);

        $converted = $leg->withUnit(DistanceUnit::METRE);

        $this->assertSame(7, $converted->getIndex());
        $this->assertTrue($converted->getFrom()->equals($this->paris()));
        $this->assertTrue($converted->getTo()->equals($this->london()));
    }

    public function test_with_unit_does_not_mutate_original(): void
    {
        $leg = $this->leg(distance: 100.0, unit: DistanceUnit::KILOMETRE);

        $leg->withUnit(DistanceUnit::METRE);

        $this->assertSame(DistanceUnit::KILOMETRE, $leg->getUnit());
        $this->assertSame(100.0, $leg->getDistance()->getValue());
    }

    // ========== GET VALUE ==========

    public function test_get_value_returns_strict_associative(): void
    {
        $leg = $this->leg();

        $value = $leg->getValue();

        $this->assertInstanceOf(Associative::class, $value);
        $this->assertSame(0, $value->index);
        $this->assertInstanceOf(CoordinatesVO::class, $value->from);
        $this->assertInstanceOf(CoordinatesVO::class, $value->to);
        $this->assertInstanceOf(FloatVO::class, $value->distance);
        $this->assertSame(DistanceUnit::KILOMETRE, $value->unit);
    }

    public function test_get_value_reflects_original_inputs(): void
    {
        $leg = $this->leg(index: 3, distance: 42.5);

        $value = $leg->getValue();

        $this->assertSame(3, $value->index);
        $this->assertSame(42.5, $value->distance->getValue());
        $this->assertTrue($value->from->equals($this->paris()));
        $this->assertTrue($value->to->equals($this->london()));
    }

    // ========== EQUALS ==========

    public function test_equals_returns_true_for_identical_legs(): void
    {
        $a = $this->leg(index: 1, distance: 100.0);
        $b = $this->leg(index: 1, distance: 100.0);

        $this->assertTrue($a->equals($b));
    }

    public function test_equals_returns_false_for_different_index(): void
    {
        $a = $this->leg(index: 1);
        $b = $this->leg(index: 2);

        $this->assertFalse($a->equals($b));
    }

    public function test_equals_returns_false_for_different_distance(): void
    {
        $a = $this->leg(distance: 100.0);
        $b = $this->leg(distance: 200.0);

        $this->assertFalse($a->equals($b));
    }

    public function test_equals_returns_false_for_different_unit(): void
    {
        $a = $this->leg(unit: DistanceUnit::KILOMETRE);
        $b = $this->leg(unit: DistanceUnit::METRE);

        $this->assertFalse($a->equals($b));
    }

    public function test_equals_returns_false_for_different_from(): void
    {
        $a = $this->leg(from: $this->paris());
        $b = $this->leg(from: $this->london());

        $this->assertFalse($a->equals($b));
    }

    public function test_equals_returns_false_for_different_to(): void
    {
        $a = $this->leg(to: $this->london());
        $b = $this->leg(to: $this->paris());

        $this->assertFalse($a->equals($b));
    }

    public function test_equals_returns_false_for_different_vo_type(): void
    {
        $leg = $this->leg();

        $this->assertFalse($leg->equals($this->paris()));
    }
}

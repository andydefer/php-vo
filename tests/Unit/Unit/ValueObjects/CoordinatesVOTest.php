<?php

declare(strict_types=1);

namespace AndyDefer\PhpVo\Tests\Unit\ValueObjects;

use AndyDefer\PhpVo\Enums\SpaceTimeUnit;
use AndyDefer\PhpVo\Records\CoordinatesRecord;
use AndyDefer\PhpVo\ValueObjects\CoordinatesVO;
use AndyDefer\PhpVo\ValueObjects\Types\BoolVO;
use AndyDefer\PhpVo\ValueObjects\Types\FloatVO;
use AndyDefer\PhpVo\ValueObjects\Types\StringVO;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class CoordinatesVOTest extends TestCase
{
    private const PARIS_COORDINATES = [
        'latitude' => 48.8566,
        'longitude' => 2.3522,
    ];

    private const LONDON_COORDINATES = [
        'latitude' => 51.5074,
        'longitude' => -0.1278,
    ];

    private const NEW_YORK_COORDINATES = [
        'latitude' => 40.7128,
        'longitude' => -74.0060,
    ];

    // ========== TESTS DE CRÉATION ==========

    public function test_create_coordinates_with_valid_data(): void
    {
        $coords = CoordinatesVO::from(self::PARIS_COORDINATES);

        $this->assertSame(48.8566, $coords->getLatitude()->getValue());
        $this->assertSame(2.3522, $coords->getLongitude()->getValue());
    }

    public function test_create_coordinates_from_float_vo(): void
    {
        $coords = new CoordinatesVO(
            FloatVO::from(48.8566),
            FloatVO::from(2.3522)
        );

        $this->assertSame(48.8566, $coords->getLatitude()->getValue());
        $this->assertSame(2.3522, $coords->getLongitude()->getValue());
    }

    public function test_create_coordinates_from_json_string(): void
    {
        $json = '{"latitude":48.8566,"longitude":2.3522}';
        $coords = CoordinatesVO::fromJson($json);

        $this->assertSame(48.8566, $coords->getLatitude()->getValue());
        $this->assertSame(2.3522, $coords->getLongitude()->getValue());
    }

    public function test_create_coordinates_from_existing_returns_same_instance(): void
    {
        $original = CoordinatesVO::from(self::PARIS_COORDINATES);
        $duplicate = CoordinatesVO::from($original);

        $this->assertSame($original, $duplicate);
    }

    // ========== TESTS DE VALIDATION ==========

    public function test_create_coordinates_with_invalid_latitude_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Latitude must be between -90.0 and 90.0, got 100.000000');

        CoordinatesVO::from(['latitude' => 100, 'longitude' => 2.3522]);
    }

    public function test_create_coordinates_with_too_low_latitude_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Latitude must be between -90.0 and 90.0, got -100.000000');

        CoordinatesVO::from(['latitude' => -100, 'longitude' => 2.3522]);
    }

    public function test_create_coordinates_with_invalid_longitude_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Longitude must be between -180.0 and 180.0, got 200.000000');

        CoordinatesVO::from(['latitude' => 48.8566, 'longitude' => 200]);
    }

    public function test_create_coordinates_with_too_low_longitude_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Longitude must be between -180.0 and 180.0, got -200.000000');

        CoordinatesVO::from(['latitude' => 48.8566, 'longitude' => -200]);
    }

    // ========== TESTS DE DISTANCE ==========

    public function test_distance_to_paris_london_in_kilometres(): void
    {
        $paris = CoordinatesVO::from(self::PARIS_COORDINATES);
        $london = CoordinatesVO::from(self::LONDON_COORDINATES);

        $distance = $paris->distanceTo($london, SpaceTimeUnit::KILOMETRE);

        $this->assertGreaterThan(340, $distance->getValue());
        $this->assertLessThan(350, $distance->getValue());
    }

    public function test_distance_to_paris_london_in_metres(): void
    {
        $paris = CoordinatesVO::from(self::PARIS_COORDINATES);
        $london = CoordinatesVO::from(self::LONDON_COORDINATES);

        $distance = $paris->distanceTo($london, SpaceTimeUnit::METRE);

        $this->assertGreaterThan(340000, $distance->getValue());
        $this->assertLessThan(350000, $distance->getValue());
    }

    public function test_distance_to_same_point_returns_zero(): void
    {
        $point1 = CoordinatesVO::from(self::PARIS_COORDINATES);
        $point2 = CoordinatesVO::from(self::PARIS_COORDINATES);

        $distance = $point1->distanceTo($point2, SpaceTimeUnit::KILOMETRE);

        $this->assertSame(0.0, $distance->getValue());
    }

    public function test_distance_to_paris_new_york(): void
    {
        $paris = CoordinatesVO::from(self::PARIS_COORDINATES);
        $newYork = CoordinatesVO::from(self::NEW_YORK_COORDINATES);

        $distance = $paris->distanceTo($newYork, SpaceTimeUnit::KILOMETRE);

        $this->assertGreaterThan(5800, $distance->getValue());
        $this->assertLessThan(5900, $distance->getValue());
    }

    public function test_distance_to_with_mixed_other(): void
    {
        $paris = CoordinatesVO::from(self::PARIS_COORDINATES);

        $distance = $paris->distanceTo(self::LONDON_COORDINATES);

        $this->assertGreaterThan(340, $distance->getValue());
        $this->assertLessThan(350, $distance->getValue());
    }

    // ========== TESTS DE COMPARAISON ==========

    public function test_is_same_location_returns_true_for_same_coordinates(): void
    {
        $point1 = CoordinatesVO::from(self::PARIS_COORDINATES);
        $point2 = CoordinatesVO::from(self::PARIS_COORDINATES);

        $this->assertTrue($point1->isSameLocation($point2)->getValue());
    }

    public function test_is_same_location_returns_false_for_different_coordinates(): void
    {
        $paris = CoordinatesVO::from(self::PARIS_COORDINATES);
        $london = CoordinatesVO::from(self::LONDON_COORDINATES);

        $this->assertFalse($paris->isSameLocation($london)->getValue());
    }

    public function test_is_same_location_with_custom_tolerance(): void
    {
        $point1 = CoordinatesVO::from(self::PARIS_COORDINATES);
        $point2 = CoordinatesVO::from([
            'latitude' => 48.8567,
            'longitude' => 2.3523,
        ]);

        $this->assertTrue($point1->isSameLocation($point2, 0.0002)->getValue());
        $this->assertFalse($point1->isSameLocation($point2, 0.00001)->getValue());
    }

    public function test_is_same_location_with_mixed_tolerance(): void
    {
        $point1 = CoordinatesVO::from(self::PARIS_COORDINATES);
        $point2 = CoordinatesVO::from([
            'latitude' => 48.8567,
            'longitude' => 2.3523,
        ]);

        $this->assertTrue($point1->isSameLocation($point2, '0.0002')->getValue());
        $this->assertFalse($point1->isSameLocation($point2, FloatVO::from(0.00001))->getValue());
    }

    // ========== TESTS DE FORMATAGE ==========

    public function test_format_returns_formatted_coordinates(): void
    {
        $coords = CoordinatesVO::from(self::PARIS_COORDINATES);

        $this->assertInstanceOf(StringVO::class, $coords->format());
        $this->assertSame('48.8566, 2.3522', $coords->format()->getValue());
    }

    public function test_format_with_custom_decimals(): void
    {
        $coords = CoordinatesVO::from(self::PARIS_COORDINATES);

        $this->assertSame('48.86, 2.35', $coords->format(2)->getValue());
        $this->assertSame('48.856600, 2.352200', $coords->format(6)->getValue());
    }

    // ========== TESTS DES HÉMISPHÈRES ==========

    public function test_is_northern_hemisphere(): void
    {
        $paris = CoordinatesVO::from(self::PARIS_COORDINATES);
        $negative = CoordinatesVO::from(['latitude' => -10, 'longitude' => 0]);

        $this->assertTrue($paris->isNorthernHemisphere()->getValue());
        $this->assertFalse($negative->isNorthernHemisphere()->getValue());
    }

    public function test_is_southern_hemisphere(): void
    {
        $paris = CoordinatesVO::from(self::PARIS_COORDINATES);
        $sydney = CoordinatesVO::from(['latitude' => -33.8688, 'longitude' => 151.2093]);

        $this->assertFalse($paris->isSouthernHemisphere()->getValue());
        $this->assertTrue($sydney->isSouthernHemisphere()->getValue());
    }

    public function test_is_eastern_hemisphere(): void
    {
        $paris = CoordinatesVO::from(self::PARIS_COORDINATES);
        $newYork = CoordinatesVO::from(self::NEW_YORK_COORDINATES);

        $this->assertTrue($paris->isEasternHemisphere()->getValue());
        $this->assertFalse($newYork->isEasternHemisphere()->getValue());
    }

    public function test_is_western_hemisphere(): void
    {
        $paris = CoordinatesVO::from(self::PARIS_COORDINATES);
        $newYork = CoordinatesVO::from(self::NEW_YORK_COORDINATES);

        $this->assertFalse($paris->isWesternHemisphere()->getValue());
        $this->assertTrue($newYork->isWesternHemisphere()->getValue());
    }

    // ========== TESTS DES MÉTHODES GETTER ==========

    public function test_get_latitude_returns_float_vo(): void
    {
        $coords = CoordinatesVO::from(self::PARIS_COORDINATES);

        $this->assertInstanceOf(FloatVO::class, $coords->getLatitude());
        $this->assertSame(48.8566, $coords->getLatitude()->getValue());
    }

    public function test_get_longitude_returns_float_vo(): void
    {
        $coords = CoordinatesVO::from(self::PARIS_COORDINATES);

        $this->assertInstanceOf(FloatVO::class, $coords->getLongitude());
        $this->assertSame(2.3522, $coords->getLongitude()->getValue());
    }

    // ========== TEST DE GETVALUE ==========

    public function test_get_value_returns_coordinates_record(): void
    {
        $coords = CoordinatesVO::from(self::PARIS_COORDINATES);
        $record = $coords->getValue();

        $this->assertInstanceOf(CoordinatesRecord::class, $record);
        $this->assertSame(48.8566, $record->latitude);
        $this->assertSame(2.3522, $record->longitude);
    }

    // ========== TESTS DE COMPARAISON ==========

    public function test_equals_method(): void
    {
        $coords1 = CoordinatesVO::from(self::PARIS_COORDINATES);
        $coords2 = CoordinatesVO::from(self::PARIS_COORDINATES);
        $coords3 = CoordinatesVO::from(self::LONDON_COORDINATES);

        $this->assertTrue($coords1->equals($coords2));
        $this->assertFalse($coords1->equals($coords3));
    }

    // ========== TEST DU TO STRING ==========

    public function test_to_string_returns_formatted_coordinates(): void
    {
        $coords = CoordinatesVO::from(self::PARIS_COORDINATES);

        $this->assertSame('48.8566, 2.3522', (string) $coords);
    }

    // ========== TESTS DE CHAÎNAGE ==========

    public function test_chaining_operations(): void
    {
        $result = CoordinatesVO::from(self::PARIS_COORDINATES)
            ->isNorthernHemisphere()
            ->and(CoordinatesVO::from(self::LONDON_COORDINATES)->isNorthernHemisphere());

        $this->assertInstanceOf(BoolVO::class, $result);
        $this->assertTrue($result->getValue());
    }

    // ========== TESTS D'IMMUTABILITÉ ==========

    public function test_immutability(): void
    {
        $original = CoordinatesVO::from(self::PARIS_COORDINATES);
        $original->distanceTo(self::LONDON_COORDINATES);
        $original->isSameLocation(self::LONDON_COORDINATES);

        $this->assertSame(48.8566, $original->getLatitude()->getValue());
        $this->assertSame(2.3522, $original->getLongitude()->getValue());
    }
}

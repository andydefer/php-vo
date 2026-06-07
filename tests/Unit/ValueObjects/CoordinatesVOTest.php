<?php

declare(strict_types=1);

namespace AndyDefer\PhpVo\Tests\Unit\ValueObjects;

use AndyDefer\PhpVo\Enums\SpaceTimeUnit;
use AndyDefer\PhpVo\Records\CoordinatesRecord;
use AndyDefer\PhpVo\ValueObjects\CoordinatesVO;
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

    public function test_create_coordinates_with_valid_data(): void
    {
        // Act
        $coords = CoordinatesVO::from(self::PARIS_COORDINATES);

        // Assert
        $this->assertSame(48.8566, $coords->latitude);
        $this->assertSame(2.3522, $coords->longitude);
    }

    public function test_create_coordinates_with_snake_case_keys(): void
    {
        // Act
        $coords = CoordinatesVO::from([
            'latitude' => 48.8566,
            'longitude' => 2.3522,
        ]);

        // Assert
        $this->assertSame(48.8566, $coords->latitude);
        $this->assertSame(2.3522, $coords->longitude);
    }

    public function test_create_coordinates_from_json_string(): void
    {
        // Arrange
        $json = '{"latitude":48.8566,"longitude":2.3522}';

        // Act
        $coords = CoordinatesVO::fromJson($json);

        // Assert
        $this->assertSame(48.8566, $coords->latitude);
        $this->assertSame(2.3522, $coords->longitude);
    }

    public function test_create_coordinates_from_existing_returns_same_instance(): void
    {
        // Arrange
        $original = CoordinatesVO::from(self::PARIS_COORDINATES);

        // Act
        $duplicate = CoordinatesVO::from($original);

        // Assert
        $this->assertSame($original, $duplicate);
    }

    public function test_create_coordinates_with_invalid_latitude_throws_exception(): void
    {
        // Expect
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Latitude must be between -90.0 and 90.0, got 100.000000');

        // Act
        CoordinatesVO::from(['latitude' => 100, 'longitude' => 2.3522]);
    }

    public function test_create_coordinates_with_too_low_latitude_throws_exception(): void
    {
        // Expect
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Latitude must be between -90.0 and 90.0, got -100.000000');

        // Act
        CoordinatesVO::from(['latitude' => -100, 'longitude' => 2.3522]);
    }

    public function test_create_coordinates_with_invalid_longitude_throws_exception(): void
    {
        // Expect
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Longitude must be between -180.0 and 180.0, got 200.000000');

        // Act
        CoordinatesVO::from(['latitude' => 48.8566, 'longitude' => 200]);
    }

    public function test_create_coordinates_with_too_low_longitude_throws_exception(): void
    {
        // Expect
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Longitude must be between -180.0 and 180.0, got -200.000000');

        // Act
        CoordinatesVO::from(['latitude' => 48.8566, 'longitude' => -200]);
    }

    public function test_distance_to_paris_london_in_kilometres(): void
    {
        // Arrange
        $paris = CoordinatesVO::from(self::PARIS_COORDINATES);
        $london = CoordinatesVO::from(self::LONDON_COORDINATES);

        // Act
        $distance = $paris->distanceTo($london, SpaceTimeUnit::KILOMETRE);

        // Assert (distance between Paris and London is approximately 344 km)
        $this->assertGreaterThan(340, $distance);
        $this->assertLessThan(350, $distance);
    }

    public function test_distance_to_paris_london_in_metres(): void
    {
        // Arrange
        $paris = CoordinatesVO::from(self::PARIS_COORDINATES);
        $london = CoordinatesVO::from(self::LONDON_COORDINATES);

        // Act
        $distance = $paris->distanceTo($london, SpaceTimeUnit::METRE);

        // Assert (distance between Paris and London is approximately 344,000 m)
        $this->assertGreaterThan(340000, $distance);
        $this->assertLessThan(350000, $distance);
    }

    public function test_distance_to_same_point_returns_zero(): void
    {
        // Arrange
        $point1 = CoordinatesVO::from(self::PARIS_COORDINATES);
        $point2 = CoordinatesVO::from(self::PARIS_COORDINATES);

        // Act
        $distance = $point1->distanceTo($point2, SpaceTimeUnit::KILOMETRE);

        // Assert
        $this->assertSame(0.0, $distance);
    }

    public function test_is_same_location_returns_true_for_same_coordinates(): void
    {
        // Arrange
        $point1 = CoordinatesVO::from(self::PARIS_COORDINATES);
        $point2 = CoordinatesVO::from(self::PARIS_COORDINATES);

        // Act & Assert
        $this->assertTrue($point1->isSameLocation($point2));
    }

    public function test_is_same_location_returns_false_for_different_coordinates(): void
    {
        // Arrange
        $paris = CoordinatesVO::from(self::PARIS_COORDINATES);
        $london = CoordinatesVO::from(self::LONDON_COORDINATES);

        // Act & Assert
        $this->assertFalse($paris->isSameLocation($london));
    }

    public function test_is_same_location_with_custom_tolerance(): void
    {
        // Arrange
        $point1 = CoordinatesVO::from(self::PARIS_COORDINATES);
        $point2 = CoordinatesVO::from([
            'latitude' => 48.8567,
            'longitude' => 2.3523,
        ]);

        // Act & Assert
        $this->assertTrue($point1->isSameLocation($point2, 0.0002));
        $this->assertFalse($point1->isSameLocation($point2, 0.00001));
    }

    public function test_format_returns_formatted_coordinates(): void
    {
        // Arrange
        $coords = CoordinatesVO::from(self::PARIS_COORDINATES);

        // Act & Assert
        $this->assertSame('48.8566, 2.3522', $coords->format());
    }

    public function test_format_with_custom_decimals(): void
    {
        // Arrange
        $coords = CoordinatesVO::from(self::PARIS_COORDINATES);

        // Act & Assert
        $this->assertSame('48.86, 2.35', $coords->format(2));
        $this->assertSame('48.856600, 2.352200', $coords->format(6));
    }

    public function test_is_northern_hemisphere(): void
    {
        // Arrange
        $paris = CoordinatesVO::from(self::PARIS_COORDINATES);
        $negative = CoordinatesVO::from(['latitude' => -10, 'longitude' => 0]);

        // Act & Assert
        $this->assertTrue($paris->isNorthernHemisphere());
        $this->assertFalse($negative->isNorthernHemisphere());
    }

    public function test_is_southern_hemisphere(): void
    {
        // Arrange
        $paris = CoordinatesVO::from(self::PARIS_COORDINATES);
        $sydney = CoordinatesVO::from(['latitude' => -33.8688, 'longitude' => 151.2093]);

        // Act & Assert
        $this->assertFalse($paris->isSouthernHemisphere());
        $this->assertTrue($sydney->isSouthernHemisphere());
    }

    public function test_is_eastern_hemisphere(): void
    {
        // Arrange
        $paris = CoordinatesVO::from(self::PARIS_COORDINATES);
        $newYork = CoordinatesVO::from(self::NEW_YORK_COORDINATES);

        // Act & Assert
        $this->assertTrue($paris->isEasternHemisphere());
        $this->assertFalse($newYork->isEasternHemisphere());
    }

    public function test_is_western_hemisphere(): void
    {
        // Arrange
        $paris = CoordinatesVO::from(self::PARIS_COORDINATES);
        $newYork = CoordinatesVO::from(self::NEW_YORK_COORDINATES);

        // Act & Assert
        $this->assertFalse($paris->isWesternHemisphere());
        $this->assertTrue($newYork->isWesternHemisphere());
    }

    public function test_get_value_returns_coordinates_record(): void
    {
        // Arrange
        $coords = CoordinatesVO::from(self::PARIS_COORDINATES);

        // Act
        $record = $coords->getValue();

        // Assert
        $this->assertInstanceOf(CoordinatesRecord::class, $record);
        $this->assertSame(48.8566, $record->latitude);
        $this->assertSame(2.3522, $record->longitude);
    }

    public function test_equals_method(): void
    {
        // Arrange
        $coords1 = CoordinatesVO::from(self::PARIS_COORDINATES);
        $coords2 = CoordinatesVO::from(self::PARIS_COORDINATES);
        $coords3 = CoordinatesVO::from(self::LONDON_COORDINATES);

        // Act & Assert
        $this->assertTrue($coords1->equals($coords2));
        $this->assertFalse($coords1->equals($coords3));
    }

    public function test_to_string_returns_formatted_coordinates(): void
    {
        // Arrange
        $coords = CoordinatesVO::from(self::PARIS_COORDINATES);

        // Assert
        $this->assertSame('48.8566, 2.3522', (string)$coords);
    }
}

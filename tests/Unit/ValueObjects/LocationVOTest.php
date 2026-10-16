<?php

declare(strict_types=1);

namespace AndyDefer\PhpVo\Tests\Unit\ValueObjects;

use AndyDefer\DomainStructures\Collections\Utility\FloatTypedCollection;
use AndyDefer\PhpVo\ValueObjects\CoordinatesVO;
use AndyDefer\PhpVo\ValueObjects\LocationVO;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class LocationVOTest extends TestCase
{
    private const KINSHASA_COORDINATES = [15.3222, -4.3250];

    private const PARIS_COORDINATES = [2.3522, 48.8566];

    // ========== TESTS DE CRÉATION ==========

    public function test_create_location_from_valid_array(): void
    {
        $location = LocationVO::fromArray(self::KINSHASA_COORDINATES);

        $this->assertSame(15.3222, $location->getLongitude());
        $this->assertSame(-4.3250, $location->getLatitude());
    }

    public function test_create_location_accepts_integers(): void
    {
        $location = LocationVO::fromArray([15, -4]);

        $this->assertSame(15.0, $location->getLongitude());
        $this->assertSame(-4.0, $location->getLatitude());
    }

    public function test_create_location_accepts_numeric_strings(): void
    {
        $location = LocationVO::fromArray(['15.3222', '-4.3250']);

        $this->assertSame(15.3222, $location->getLongitude());
        $this->assertSame(-4.3250, $location->getLatitude());
    }

    public function test_create_location_from_collection(): void
    {
        $collection = new FloatTypedCollection;
        $collection->add(15.3222);
        $collection->add(-4.3250);

        $location = new LocationVO($collection);

        $this->assertSame(15.3222, $location->getLongitude());
        $this->assertSame(-4.3250, $location->getLatitude());
    }

    // ========== TESTS DE VALIDATION ==========

    public function test_create_location_with_too_few_coordinates_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Location must contain exactly 2 floats, 1 given.');

        LocationVO::fromArray([15.3222]);
    }

    public function test_create_location_with_too_many_coordinates_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Location must contain exactly 2 floats, 3 given.');

        LocationVO::fromArray([15.3222, -4.3250, 100.0]);
    }

    public function test_create_location_with_empty_array_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Location must contain exactly 2 floats, 0 given.');

        LocationVO::fromArray([]);
    }

    public function test_create_location_with_non_numeric_coordinate_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Location coordinate must be numeric, string given.');

        LocationVO::fromArray([15.3222, 'not-a-number']);
    }

    public function test_create_location_with_null_coordinate_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Location coordinate must be numeric, null given.');

        LocationVO::fromArray([15.3222, null]);
    }

    // ========== TESTS DES GETTERS ==========

    public function test_get_longitude_returns_first_element(): void
    {
        $location = LocationVO::fromArray(self::PARIS_COORDINATES);

        $this->assertSame(2.3522, $location->getLongitude());
    }

    public function test_get_latitude_returns_second_element(): void
    {
        $location = LocationVO::fromArray(self::PARIS_COORDINATES);

        $this->assertSame(48.8566, $location->getLatitude());
    }

    public function test_get_coordinate_count_returns_two(): void
    {
        $location = LocationVO::fromArray(self::PARIS_COORDINATES);

        $this->assertSame(2, $location->getCoordinateCount());
    }

    public function test_get_value_returns_float_typed_collection(): void
    {
        $location = LocationVO::fromArray(self::PARIS_COORDINATES);

        $collection = $location->getValue();

        $this->assertInstanceOf(FloatTypedCollection::class, $collection);
        $this->assertSame([2.3522, 48.8566], $collection->toArray());
    }

    // ========== TESTS DE CONVERSION ==========

    public function test_to_coordinates_returns_coordinates_vo(): void
    {
        $location = LocationVO::fromArray(self::PARIS_COORDINATES);

        $coords = $location->toCoordinates();

        $this->assertInstanceOf(CoordinatesVO::class, $coords);
        $this->assertSame(48.8566, $coords->getLatitude()->getValue());
        $this->assertSame(2.3522, $coords->getLongitude()->getValue());
    }

    public function test_to_coordinates_preserves_negative_coordinates(): void
    {
        $location = LocationVO::fromArray(self::KINSHASA_COORDINATES);

        $coords = $location->toCoordinates();

        $this->assertSame(-4.3250, $coords->getLatitude()->getValue());
        $this->assertSame(15.3222, $coords->getLongitude()->getValue());
    }

    public function test_round_trip_location_to_coordinates_to_location(): void
    {
        $original = LocationVO::fromArray(self::PARIS_COORDINATES);

        $restored = $original->toCoordinates()->toLocation();

        $this->assertSame($original->getLongitude(), $restored->getLongitude());
        $this->assertSame($original->getLatitude(), $restored->getLatitude());
    }

    public function test_to_array_returns_raw_scalar_array(): void
    {
        $location = LocationVO::fromArray(self::PARIS_COORDINATES);

        $this->assertSame([2.3522, 48.8566], $location->toArray());
    }

    // ========== TESTS DE WITH ==========

    public function test_with_longitude_returns_new_instance(): void
    {
        $location = LocationVO::fromArray(self::PARIS_COORDINATES);

        $updated = $location->withLongitude(10.0);

        $this->assertNotSame($location, $updated);
        $this->assertSame(10.0, $updated->getLongitude());
        $this->assertSame(48.8566, $updated->getLatitude());
    }

    public function test_with_latitude_returns_new_instance(): void
    {
        $location = LocationVO::fromArray(self::PARIS_COORDINATES);

        $updated = $location->withLatitude(10.0);

        $this->assertNotSame($location, $updated);
        $this->assertSame(2.3522, $updated->getLongitude());
        $this->assertSame(10.0, $updated->getLatitude());
    }

    public function test_with_longitude_does_not_mutate_original(): void
    {
        $original = LocationVO::fromArray(self::PARIS_COORDINATES);

        $original->withLongitude(10.0);

        $this->assertSame(2.3522, $original->getLongitude());
        $this->assertSame(48.8566, $original->getLatitude());
    }

    public function test_with_latitude_does_not_mutate_original(): void
    {
        $original = LocationVO::fromArray(self::PARIS_COORDINATES);

        $original->withLatitude(10.0);

        $this->assertSame(2.3522, $original->getLongitude());
        $this->assertSame(48.8566, $original->getLatitude());
    }

    // ========== TESTS DE COMPARAISON ==========

    public function test_equals_returns_true_for_same_coordinates(): void
    {
        $location1 = LocationVO::fromArray(self::PARIS_COORDINATES);
        $location2 = LocationVO::fromArray(self::PARIS_COORDINATES);

        $this->assertTrue($location1->equals($location2));
    }

    public function test_equals_returns_false_for_different_coordinates(): void
    {
        $location1 = LocationVO::fromArray(self::PARIS_COORDINATES);
        $location2 = LocationVO::fromArray(self::KINSHASA_COORDINATES);

        $this->assertFalse($location1->equals($location2));
    }

    public function test_equals_returns_false_for_different_vo_type(): void
    {
        $location = LocationVO::fromArray(self::PARIS_COORDINATES);
        $coords = CoordinatesVO::from([
            'latitude' => 48.8566,
            'longitude' => 2.3522,
        ]);

        $this->assertFalse($location->equals($coords));
    }

    // ========== TESTS DU TO STRING ==========

    public function test_to_string_formats_longitude_then_latitude(): void
    {
        $location = LocationVO::fromArray(self::PARIS_COORDINATES);

        $this->assertSame('2.352200,48.856600', (string) $location);
    }

    public function test_to_string_with_negative_values(): void
    {
        $location = LocationVO::fromArray(self::KINSHASA_COORDINATES);

        $this->assertSame('15.322200,-4.325000', (string) $location);
    }

    // ========== TESTS D'IMMUTABILITÉ ==========

    public function test_immutability(): void
    {
        $original = LocationVO::fromArray(self::PARIS_COORDINATES);
        $original->toCoordinates();
        $original->getValue();

        $this->assertSame(2.3522, $original->getLongitude());
        $this->assertSame(48.8566, $original->getLatitude());
    }
}

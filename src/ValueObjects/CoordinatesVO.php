<?php

declare(strict_types=1);

namespace AndyDefer\PhpVo\ValueObjects;

use AndyDefer\DomainStructures\Abstracts\AbstractValueObject;
use AndyDefer\PhpVo\Configs\CoordinatesConfig;
use AndyDefer\PhpVo\Contracts\CoordinatesConfigInterface;
use AndyDefer\PhpVo\Enums\SpaceTimeUnit;
use AndyDefer\PhpVo\Records\CoordinatesRecord;
use AndyDefer\PhpVo\ValueObjects\Types\BoolVO;
use AndyDefer\PhpVo\ValueObjects\Types\FloatVO;
use AndyDefer\PhpVo\ValueObjects\Types\StringVO;
use InvalidArgumentException;

/**
 * Immutable Value Object representing geographic coordinates.
 *
 * Encapsulates latitude and longitude with validation, distance calculation,
 * and hemisphere detection. All operations return new instances.
 *
 * @example
 * $coords = new CoordinatesVO(
 *     FloatVO::from(48.8566),
 *     FloatVO::from(2.3522)
 * );
 *
 * echo $coords->format(); // '48.8566, 2.3522'
 * echo $coords->distanceTo($other); // Distance in kilometers
 */
final class CoordinatesVO extends AbstractValueObject
{
    private const EARTH_RADIUS_KM = 6371.0;

    private const DEFAULT_TOLERANCE = 0.0001;

    private const DEGREES_TO_RADIANS = M_PI / 180;

    private static ?CoordinatesConfigInterface $config = null;

    public function __construct(
        private readonly FloatVO $latitude,
        private readonly FloatVO $longitude,
    ) {
        $this->validate();
    }

    /**
     * Gets the configuration instance.
     */
    private static function getConfig(): CoordinatesConfigInterface
    {
        return self::$config ??= new CoordinatesConfig;
    }

    /**
     * Validates latitude and longitude ranges.
     *
     * @throws InvalidArgumentException If coordinates are out of bounds
     */
    private function validate(): void
    {
        $config = self::getConfig();

        $this->validateLatitude($config);
        $this->validateLongitude($config);
    }

    /**
     * Validates the latitude is within bounds.
     */
    private function validateLatitude(CoordinatesConfigInterface $config): void
    {
        $lat = $this->latitude;

        if ($lat->lessThan($config->latitudeMin())->getValue() ||
            $lat->greaterThan($config->latitudeMax())->getValue()) {
            throw new InvalidArgumentException(
                $this->buildOutOfBoundsMessage(
                    'Latitude',
                    $config->latitudeMin(),
                    $config->latitudeMax(),
                    $lat
                )
            );
        }
    }

    /**
     * Validates the longitude is within bounds.
     */
    private function validateLongitude(CoordinatesConfigInterface $config): void
    {
        $lon = $this->longitude;

        if ($lon->lessThan($config->longitudeMin())->getValue() ||
            $lon->greaterThan($config->longitudeMax())->getValue()) {
            throw new InvalidArgumentException(
                $this->buildOutOfBoundsMessage(
                    'Longitude',
                    $config->longitudeMin(),
                    $config->longitudeMax(),
                    $lon
                )
            );
        }
    }

    /**
     * Builds a formatted out-of-bounds error message.
     */
    private function buildOutOfBoundsMessage(
        string $coordinateName,
        FloatVO $min,
        FloatVO $max,
        FloatVO $value
    ): string {
        return StringVO::from('{name} must be between {min} and {max}, got {value}')
            ->format([
                'name' => $coordinateName,
                'min' => $min->format(1)->getValue(),
                'max' => $max->format(1)->getValue(),
                'value' => $value->format(6)->getValue(),
            ])
            ->getValue();
    }

    /**
     * Converts degrees to radians.
     */
    private function toRadians(FloatVO $degrees): FloatVO
    {
        return $degrees->multiply(self::DEGREES_TO_RADIANS);
    }

    /**
     * Calculates the central angle using the Haversine formula.
     *
     * @see https://en.wikipedia.org/wiki/Haversine_formula
     */
    private function calculateCentralAngle(self $other): FloatVO
    {
        $latFrom = $this->toRadians($this->latitude);
        $latTo = $this->toRadians($other->latitude);
        $lonFrom = $this->toRadians($this->longitude);
        $lonTo = $this->toRadians($other->longitude);

        $latDelta = $latTo->subtract($latFrom);
        $lonDelta = $lonTo->subtract($lonFrom);

        // a = sin²(Δlat/2) + cos(lat1) * cos(lat2) * sin²(Δlon/2)
        $a = $latDelta->divide(2)
            ->sin()
            ->pow(2)
            ->add(
                $latFrom->cos()
                    ->multiply($latTo->cos())
                    ->multiply(
                        $lonDelta->divide(2)
                            ->sin()
                            ->pow(2)
                    )
            );

        // c = 2 * atan2(√a, √(1-a))
        return $a->clamp(0, 1)
            ->sqrt()
            ->atan2(
                $a->subtract(1)
                    ->abs()
                    ->sqrt()
            )
            ->multiply(2);
    }

    /**
     * Returns the coordinates as a record.
     */
    public function getValue(): CoordinatesRecord
    {
        return new CoordinatesRecord(
            latitude: $this->latitude->getValue(),
            longitude: $this->longitude->getValue(),
        );
    }

    /**
     * Calculates the distance to another coordinate.
     *
     * @param  self|CoordinatesRecord|array<string, float>|string  $other  The target coordinates
     * @param  SpaceTimeUnit  $unit  Unit of measurement (Kilometre or Metre)
     * @return FloatVO Distance in the specified unit (rounded to 2 decimals)
     */
    public function distanceTo(mixed $other, SpaceTimeUnit $unit = SpaceTimeUnit::KILOMETRE): FloatVO
    {
        $target = self::from($other);

        $centralAngle = $this->calculateCentralAngle($target);
        $radius = $this->getEarthRadius($unit);

        return $centralAngle
            ->multiply($radius)
            ->round(2);
    }

    /**
     * Gets the Earth radius for the specified unit.
     */
    private function getEarthRadius(SpaceTimeUnit $unit): FloatVO
    {
        $radius = FloatVO::from(self::EARTH_RADIUS_KM);

        if ($unit === SpaceTimeUnit::METRE) {
            return $radius->multiply(1000);
        }

        return $radius;
    }

    /**
     * Checks if two coordinates are at the same location within tolerance.
     *
     * @param  self|CoordinatesRecord|array<string, float>|string  $other  The coordinates to compare
     * @param  float|null  $tolerance  Tolerance for comparison (default: 0.0001)
     * @return BoolVO True if coordinates are within tolerance
     */
    public function isSameLocation(mixed $other, ?float $tolerance = null): BoolVO
    {
        $target = self::from($other);
        $tolerance = FloatVO::from($tolerance ?? self::DEFAULT_TOLERANCE);

        $latDiff = $this->latitude->subtract($target->latitude)->abs();
        $lonDiff = $this->longitude->subtract($target->longitude)->abs();

        $isSameLat = $latDiff->lessThan($tolerance)->getValue();
        $isSameLon = $lonDiff->lessThan($tolerance)->getValue();

        return BoolVO::from($isSameLat && $isSameLon);
    }

    /**
     * Formats the coordinates as a string.
     *
     * @param  int  $decimals  Number of decimal places (default: 4)
     * @return StringVO Formatted string: "latitude, longitude"
     */
    public function format(int $decimals = 4): StringVO
    {
        return StringVO::from(
            sprintf(
                '%.'.$decimals.'f, %.'.$decimals.'f',
                $this->latitude->getValue(),
                $this->longitude->getValue()
            )
        );
    }

    /**
     * Checks if the location is in the Northern Hemisphere.
     *
     * @return BoolVO True if latitude > 0
     */
    public function isNorthernHemisphere(): BoolVO
    {
        return $this->latitude->greaterThan(0);
    }

    /**
     * Checks if the location is in the Southern Hemisphere.
     *
     * @return BoolVO True if latitude < 0
     */
    public function isSouthernHemisphere(): BoolVO
    {
        return $this->latitude->lessThan(0);
    }

    /**
     * Checks if the location is in the Eastern Hemisphere.
     *
     * @return BoolVO True if longitude > 0
     */
    public function isEasternHemisphere(): BoolVO
    {
        return $this->longitude->greaterThan(0);
    }

    /**
     * Checks if the location is in the Western Hemisphere.
     *
     * @return BoolVO True if longitude < 0
     */
    public function isWesternHemisphere(): BoolVO
    {
        return $this->longitude->lessThan(0);
    }

    /**
     * Gets the latitude value.
     */
    public function getLatitude(): FloatVO
    {
        return $this->latitude;
    }

    /**
     * Gets the longitude value.
     */
    public function getLongitude(): FloatVO
    {
        return $this->longitude;
    }

    /**
     * Magic method for string representation.
     */
    public function __toString(): string
    {
        return $this->format()->getValue();
    }
}

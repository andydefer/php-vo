<?php

declare(strict_types=1);

namespace AndyDefer\PhpVo\ValueObjects;

use AndyDefer\DomainStructures\Abstracts\AbstractValueObject;
use AndyDefer\PhpVo\Configs\CoordinatesConfig;
use AndyDefer\PhpVo\Enums\SpaceTimeUnit;
use AndyDefer\PhpVo\Records\CoordinatesRecord;
use InvalidArgumentException;

final class CoordinatesVO extends AbstractValueObject
{
    private static ?CoordinatesConfig $config = null;

    public function __construct(
        public readonly float $latitude,
        public readonly float $longitude,
    ) {
        $this->validate($latitude, $longitude);
    }

    private static function getConfig(): CoordinatesConfig
    {
        if (self::$config === null) {
            self::$config = new CoordinatesConfig();
        }

        return self::$config;
    }

    public function getValue(): CoordinatesRecord
    {
        return new CoordinatesRecord(
            latitude: $this->latitude,
            longitude: $this->longitude,
        );
    }

    private function validate(float $latitude, float $longitude): void
    {
        $config = self::getConfig();

        if ($latitude < $config->latitudeMin() || $latitude > $config->latitudeMax()) {
            throw new InvalidArgumentException(
                sprintf(
                    'Latitude must be between %.1f and %.1f, got %.6f',
                    $config->latitudeMin(),
                    $config->latitudeMax(),
                    $latitude
                )
            );
        }

        if ($longitude < $config->longitudeMin() || $longitude > $config->longitudeMax()) {
            throw new InvalidArgumentException(
                sprintf(
                    'Longitude must be between %.1f and %.1f, got %.6f',
                    $config->longitudeMin(),
                    $config->longitudeMax(),
                    $longitude
                )
            );
        }
    }

    public function distanceTo(self $other, SpaceTimeUnit $unit = SpaceTimeUnit::KILOMETRE): float
    {
        $config = self::getConfig();

        $latFrom = deg2rad($this->latitude);
        $latTo = deg2rad($other->latitude);
        $lonFrom = deg2rad($this->longitude);
        $lonTo = deg2rad($other->longitude);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $a = sin($latDelta / 2) ** 2 +
            cos($latFrom) * cos($latTo) *
            sin($lonDelta / 2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $radiusInKm = $config->earthRadiusKm();
        $radius = match ($unit) {
            SpaceTimeUnit::KILOMETRE => $radiusInKm,
            SpaceTimeUnit::METRE => $radiusInKm * 1000,
            default => $radiusInKm,
        };

        return round($c * $radius, 2);
    }

    public function isSameLocation(self $other, float $tolerance = 0.0001): bool
    {
        return abs($this->latitude - $other->latitude) <= $tolerance &&
            abs($this->longitude - $other->longitude) <= $tolerance;
    }

    public function format(int $decimals = 4): string
    {
        return sprintf(
            '%.' . $decimals . 'f, %.' . $decimals . 'f',
            $this->latitude,
            $this->longitude
        );
    }

    public function isNorthernHemisphere(): bool
    {
        return $this->latitude > 0;
    }

    public function isSouthernHemisphere(): bool
    {
        return $this->latitude < 0;
    }

    public function isEasternHemisphere(): bool
    {
        return $this->longitude > 0;
    }

    public function isWesternHemisphere(): bool
    {
        return $this->longitude < 0;
    }

    public function __toString(): string
    {
        return $this->format();
    }
}

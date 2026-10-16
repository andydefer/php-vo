<?php

declare(strict_types=1);

namespace AndyDefer\PhpVo\ValueObjects\Abstracts;

use AndyDefer\DomainStructures\Abstracts\AbstractValueObject;
use AndyDefer\PhpVo\Enums\SpaceTimeUnit;
use AndyDefer\PhpVo\ValueObjects\Strings\UuidVO;
use AndyDefer\PhpVo\ValueObjects\Types\BoolVO;
use AndyDefer\PhpVo\ValueObjects\Types\FloatVO;
use AndyDefer\PhpVo\ValueObjects\Types\StringVO;

/**
 * Abstract base for Value Objects representing a geographic point.
 *
 * Provides geographic behaviour (distance, tolerance comparison, hemisphere
 * detection, formatting) and optional labels (UUID, name, description).
 *
 * IMPORTANT: this abstract deliberately does NOT declare getLatitude() or
 * getLongitude(). Each concrete VO defines its own public signatures
 * (FloatVO for CoordinatesVO, float for LocationVO) so their existing APIs
 * remain untouched.
 *
 * Subclasses must implement:
 *   - latitudeValue(): float
 *   - longitudeValue(): float
 *   - copyWithLabels(): static
 */
abstract class AbstractCoordinatesVO extends AbstractValueObject
{
    protected const EARTH_RADIUS_KM = 6371.0;

    protected const DEFAULT_TOLERANCE = 0.0001;

    protected const DEGREES_TO_RADIANS = M_PI / 180;

    /**
     * Latitude as a raw float.
     */
    abstract protected function latitudeValue(): float;

    /**
     * Longitude as a raw float.
     */
    abstract protected function longitudeValue(): float;

    /**
     * Return a new instance with the given labels.
     */
    abstract protected function copyWithLabels(
        ?UuidVO $uuid,
        ?StringVO $name,
        ?StringVO $description,
    ): static;

    // ============================================================
    // GEOGRAPHY
    // ============================================================

    public function distanceTo(
        self $other,
        SpaceTimeUnit $unit = SpaceTimeUnit::KILOMETRE,
    ): FloatVO {
        $centralAngle = $this->centralAngle(
            FloatVO::from($this->latitudeValue()),
            FloatVO::from($other->latitudeValue()),
            FloatVO::from($this->longitudeValue()),
            FloatVO::from($other->longitudeValue()),
        );

        return $centralAngle
            ->multiply($this->earthRadius($unit))
            ->round(2);
    }

    public function isSameLocation(
        self $other,
        ?float $tolerance = null,
    ): BoolVO {
        $toleranceVO = FloatVO::from($tolerance ?? self::DEFAULT_TOLERANCE);

        $latDiff = abs($this->latitudeValue() - $other->latitudeValue());
        $lonDiff = abs($this->longitudeValue() - $other->longitudeValue());

        $isSameLat = $latDiff < $toleranceVO->getValue();
        $isSameLon = $lonDiff < $toleranceVO->getValue();

        return BoolVO::from($isSameLat && $isSameLon);
    }

    public function format(int $decimals = 4): StringVO
    {
        return StringVO::from(sprintf(
            '%.'.$decimals.'f, %.'.$decimals.'f',
            $this->latitudeValue(),
            $this->longitudeValue(),
        ));
    }

    public function isNorthernHemisphere(): BoolVO
    {
        return BoolVO::from($this->latitudeValue() > 0);
    }

    public function isSouthernHemisphere(): BoolVO
    {
        return BoolVO::from($this->latitudeValue() < 0);
    }

    public function isEasternHemisphere(): BoolVO
    {
        return BoolVO::from($this->longitudeValue() > 0);
    }

    public function isWesternHemisphere(): BoolVO
    {
        return BoolVO::from($this->longitudeValue() < 0);
    }

    // ============================================================
    // LABELS
    // ============================================================

    public function getUuid(): ?UuidVO
    {
        return $this->resolveUuid();
    }

    public function getName(): ?StringVO
    {
        return $this->resolveName();
    }

    public function getDescription(): ?StringVO
    {
        return $this->resolveDescription();
    }

    public function isNamed(): bool
    {
        return $this->resolveUuid() !== null
            || $this->resolveName() !== null
            || $this->resolveDescription() !== null;
    }

    public function withUuid(UuidVO|string|null $uuid): static
    {
        if (is_string($uuid)) {
            $uuid = new UuidVO($uuid);
        }

        return $this->copyWithLabels(
            $uuid,
            $this->resolveName(),
            $this->resolveDescription(),
        );
    }

    public function withName(StringVO|string|null $name): static
    {
        if (is_string($name)) {
            $name = StringVO::from($name);
        }

        return $this->copyWithLabels(
            $this->resolveUuid(),
            $name,
            $this->resolveDescription(),
        );
    }

    public function withDescription(StringVO|string|null $description): static
    {
        if (is_string($description)) {
            $description = StringVO::from($description);
        }

        return $this->copyWithLabels(
            $this->resolveUuid(),
            $this->resolveName(),
            $description,
        );
    }

    // ============================================================
    // LABEL HOOKS
    // ============================================================

    protected function resolveUuid(): ?UuidVO
    {
        /** @phpstan-ignore-next-line */
        return $this->uuid ?? null;
    }

    protected function resolveName(): ?StringVO
    {
        /** @phpstan-ignore-next-line */
        return $this->name ?? null;
    }

    protected function resolveDescription(): ?StringVO
    {
        /** @phpstan-ignore-next-line */
        return $this->description ?? null;
    }

    // ============================================================
    // HAVERSINE HELPERS
    // ============================================================

    private function earthRadius(SpaceTimeUnit $unit): FloatVO
    {
        $radius = FloatVO::from(self::EARTH_RADIUS_KM);

        return $unit === SpaceTimeUnit::METRE
            ? $radius->multiply(1000)
            : $radius;
    }

    private function toRadians(FloatVO $degrees): FloatVO
    {
        return $degrees->multiply(self::DEGREES_TO_RADIANS);
    }

    private function centralAngle(
        FloatVO $latFrom,
        FloatVO $latTo,
        FloatVO $lonFrom,
        FloatVO $lonTo,
    ): FloatVO {
        $latFromRad = $this->toRadians($latFrom);
        $latToRad = $this->toRadians($latTo);
        $lonFromRad = $this->toRadians($lonFrom);
        $lonToRad = $this->toRadians($lonTo);

        $latDelta = $latToRad->subtract($latFromRad);
        $lonDelta = $lonToRad->subtract($lonFromRad);

        $a = $latDelta->divide(2)
            ->sin()
            ->pow(2)
            ->add(
                $latFromRad->cos()
                    ->multiply($latToRad->cos())
                    ->multiply(
                        $lonDelta->divide(2)
                            ->sin()
                            ->pow(2)
                    )
            );

        return $a->clamp(0, 1)
            ->sqrt()
            ->atan2(
                $a->subtract(1)
                    ->abs()
                    ->sqrt()
            )
            ->multiply(2);
    }
}

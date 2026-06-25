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

final class CoordinatesVO extends AbstractValueObject
{
    private static ?CoordinatesConfigInterface $config = null;

    public function __construct(
        private readonly FloatVO $latitude,
        private readonly FloatVO $longitude,
    ) {
        $this->validate();
    }

    private static function getConfig(): CoordinatesConfigInterface
    {
        return self::$config ??= new CoordinatesConfig;
    }

    private function validate(): void
    {
        $config = self::getConfig();

        if ($this->latitude->lessThan($config->latitudeMin())->getValue() ||
            $this->latitude->greaterThan($config->latitudeMax())->getValue()) {
            throw new InvalidArgumentException(
                StringVO::from('Latitude must be between {min} and {max}, got {value}')
                    ->format([
                        'min' => $config->latitudeMin()->format(1),
                        'max' => $config->latitudeMax()->format(1),
                        'value' => $this->latitude->format(6),
                    ])
                    ->getValue()
            );
        }

        if ($this->longitude->lessThan($config->longitudeMin())->getValue() ||
            $this->longitude->greaterThan($config->longitudeMax())->getValue()) {
            throw new InvalidArgumentException(
                StringVO::from('Longitude must be between {min} and {max}, got {value}')
                    ->format([
                        'min' => $config->longitudeMin()->format(1)->getValue(),
                        'max' => $config->longitudeMax()->format(1)->getValue(),
                        'value' => $this->longitude->format(6)->getValue(),
                    ])
                    ->getValue()
            );
        }
    }

    public function getValue(): CoordinatesRecord
    {
        return new CoordinatesRecord(
            latitude: $this->latitude->getValue(),
            longitude: $this->longitude->getValue(),
        );
    }

    public function distanceTo(mixed $other, SpaceTimeUnit $unit = SpaceTimeUnit::KILOMETRE): FloatVO
    {
        $other = self::from($other);

        $latFrom = $this->latitude->multiply(M_PI / 180);
        $latTo = $other->latitude->multiply(M_PI / 180);
        $lonFrom = $this->longitude->multiply(M_PI / 180);
        $lonTo = $other->longitude->multiply(M_PI / 180);

        $latDelta = $latTo->subtract($latFrom);
        $lonDelta = $lonTo->subtract($lonFrom);

        $a = $latDelta->divide(2)->sin()->pow(2)->add(
            $latFrom->cos()->multiply($latTo->cos())->multiply(
                $lonDelta->divide(2)->sin()->pow(2)
            )
        );

        // ✅ Chaînage complet avec gestion des erreurs d'arrondi
        $c = $a->clamp(0, 1)
            ->sqrt()
            ->atan2(
                $a->subtract(1)->abs()->sqrt()
            )
            ->multiply(2);

        $radius = self::getConfig()->earthRadiusKm();

        if ($unit === SpaceTimeUnit::METRE) {
            $radius = $radius->multiply(1000);
        }

        return $c->multiply($radius)->round(2);
    }

    public function isSameLocation(mixed $other, mixed $tolerance = null): BoolVO
    {
        $other = self::from($other);
        $tolerance = $tolerance !== null ? FloatVO::from($tolerance)->getValue() : 0.0001;

        return BoolVO::from(
            $this->latitude->subtract($other->latitude)->abs()->lessThan($tolerance)->getValue() &&
            $this->longitude->subtract($other->longitude)->abs()->lessThan($tolerance)->getValue()
        );
    }

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

    public function isNorthernHemisphere(): BoolVO
    {
        return $this->latitude->greaterThan(0);
    }

    public function isSouthernHemisphere(): BoolVO
    {
        return $this->latitude->lessThan(0);
    }

    public function isEasternHemisphere(): BoolVO
    {
        return $this->longitude->greaterThan(0);
    }

    public function isWesternHemisphere(): BoolVO
    {
        return $this->longitude->lessThan(0);
    }

    public function getLatitude(): FloatVO
    {
        return $this->latitude;
    }

    public function getLongitude(): FloatVO
    {
        return $this->longitude;
    }

    public function __toString(): string
    {
        return $this->format()->getValue();
    }
}

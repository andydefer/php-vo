<?php

declare(strict_types=1);

namespace AndyDefer\PhpVo\ValueObjects;

use AndyDefer\DomainStructures\Abstracts\AbstractValueObject;
use AndyDefer\DomainStructures\Utils\Associative;
use AndyDefer\PhpVo\Configs\CoordinatesConfig;
use AndyDefer\PhpVo\Contracts\CoordinatesConfigInterface;
use AndyDefer\PhpVo\ValueObjects\Abstracts\AbstractCoordinatesVO;
use AndyDefer\PhpVo\ValueObjects\Strings\UuidVO;
use AndyDefer\PhpVo\ValueObjects\Types\FloatVO;
use AndyDefer\PhpVo\ValueObjects\Types\StringVO;
use InvalidArgumentException;

final class CoordinatesVO extends AbstractCoordinatesVO
{
    private static ?CoordinatesConfigInterface $config = null;

    public function __construct(
        private readonly FloatVO $latitude,
        private readonly FloatVO $longitude,
        private readonly ?UuidVO $uuid = null,
        private readonly ?StringVO $name = null,
        private readonly ?StringVO $description = null,
    ) {
        $this->validate();
    }

    /**
     * Public accessor returning a FloatVO (unchanged).
     */
    public function getLatitude(): FloatVO
    {
        return $this->latitude;
    }

    /**
     * Public accessor returning a FloatVO (unchanged).
     */
    public function getLongitude(): FloatVO
    {
        return $this->longitude;
    }

    protected function latitudeValue(): float
    {
        return $this->latitude->getValue();
    }

    protected function longitudeValue(): float
    {
        return $this->longitude->getValue();
    }

    public function getValue(): Associative
    {
        return Associative::from([
            'latitude' => $this->latitude->getValue(),
            'longitude' => $this->longitude->getValue(),
            'uuid' => $this->uuid?->getValue(),
            'name' => $this->name?->getValue(),
            'description' => $this->description?->getValue(),
        ]);
    }

    public function toLocation(): LocationVO
    {
        return LocationVO::fromArray([
            $this->longitude->getValue(),
            $this->latitude->getValue(),
        ]);
    }

    public function toCoordinates(): self
    {
        return new self(
            $this->latitude,
            $this->longitude,
            $this->uuid,
            $this->name,
            $this->description,
        );
    }

    protected function copyWithLabels(
        ?UuidVO $uuid,
        ?StringVO $name,
        ?StringVO $description,
    ): static {
        return new self(
            $this->latitude,
            $this->longitude,
            $uuid,
            $name,
            $description,
        );
    }

    public function equals(AbstractValueObject $other): bool
    {
        if (! $other instanceof self) {
            return false;
        }

        if (! $this->latitude->equals($other->latitude)) {
            return false;
        }

        if (! $this->longitude->equals($other->longitude)) {
            return false;
        }

        if (($this->uuid === null) !== ($other->uuid === null)) {
            return false;
        }

        if ($this->uuid !== null && ! $this->uuid->equals($other->uuid)) {
            return false;
        }

        if (($this->name === null) !== ($other->name === null)) {
            return false;
        }

        if ($this->name !== null && ! $this->name->equals($other->name)) {
            return false;
        }

        if (($this->description === null) !== ($other->description === null)) {
            return false;
        }

        if ($this->description !== null && ! $this->description->equals($other->description)) {
            return false;
        }

        return true;
    }

    public function __toString(): string
    {
        return $this->format()->getValue();
    }

    // ============================================================
    // VALIDATION
    // ============================================================

    private static function getConfig(): CoordinatesConfigInterface
    {
        return self::$config ??= new CoordinatesConfig;
    }

    private function validate(): void
    {
        $config = self::getConfig();

        $this->validateLatitude($config);
        $this->validateLongitude($config);
    }

    private function validateLatitude(CoordinatesConfigInterface $config): void
    {
        if ($this->latitude->lessThan($config->latitudeMin())->getValue()
            || $this->latitude->greaterThan($config->latitudeMax())->getValue()) {
            throw new InvalidArgumentException($this->outOfBounds(
                'Latitude',
                $config->latitudeMin(),
                $config->latitudeMax(),
                $this->latitude,
            ));
        }
    }

    private function validateLongitude(CoordinatesConfigInterface $config): void
    {
        if ($this->longitude->lessThan($config->longitudeMin())->getValue()
            || $this->longitude->greaterThan($config->longitudeMax())->getValue()) {
            throw new InvalidArgumentException($this->outOfBounds(
                'Longitude',
                $config->longitudeMin(),
                $config->longitudeMax(),
                $this->longitude,
            ));
        }
    }

    private function outOfBounds(
        string $name,
        FloatVO $min,
        FloatVO $max,
        FloatVO $value,
    ): string {
        return StringVO::from('{name} must be between {min} and {max}, got {value}')
            ->format([
                'name' => $name,
                'min' => $min->format(1)->getValue(),
                'max' => $max->format(1)->getValue(),
                'value' => $value->format(6)->getValue(),
            ])
            ->getValue();
    }
}

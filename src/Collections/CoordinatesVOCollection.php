<?php

declare(strict_types=1);

namespace AndyDefer\PhpVo\Collections;

use AndyDefer\DomainStructures\Abstracts\AbstractTypedCollection;
use AndyDefer\PhpVo\ValueObjects\CoordinatesVO;

/**
 * Collection of CoordinatesVO instances.
 *
 * Provides helpers to append, serialize and aggregate geographic coordinates.
 *
 * @extends AbstractTypedCollection<CoordinatesVO>
 */
final class CoordinatesVOCollection extends AbstractTypedCollection
{
    public function __construct()
    {
        parent::__construct(CoordinatesVO::class);
    }

    /**
     * Return the raw scalar values of every coordinate in the collection.
     *
     * Each entry is shaped as `['latitude' => float, 'longitude' => float]`.
     *
     * @return array<int, array{latitude: float, longitude: float}>
     */
    public function toValues(): array
    {
        return $this->map(
            fn (CoordinatesVO $coordinates): array => [
                'latitude' => $coordinates->getLatitude()->getValue(),
                'longitude' => $coordinates->getLongitude()->getValue(),
            ],
        )->toArray();
    }

    /**
     * Convert every coordinates VO to a LocationVO.
     */
    public function toLocations(): LocationVOCollection
    {
        $collection = new LocationVOCollection;

        foreach ($this as $coordinates) {
            $collection->add($coordinates->toLocation());
        }

        return $collection;
    }

    /**
     * Return the coordinates as `[latitude, longitude]` pairs.
     *
     * @return array<int, array{0: float, 1: float}>
     */
    public function toPairs(): array
    {
        return $this->map(
            fn (CoordinatesVO $coordinates): array => [
                $coordinates->getLatitude()->getValue(),
                $coordinates->getLongitude()->getValue(),
            ],
        )->toArray();
    }
}

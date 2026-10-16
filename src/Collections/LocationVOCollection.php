<?php

declare(strict_types=1);

namespace AndyDefer\PhpVo\Collections;

use AndyDefer\DomainStructures\Abstracts\AbstractTypedCollection;
use AndyDefer\PhpVo\ValueObjects\LocationVO;

/**
 * Collection of LocationVO instances.
 *
 * Provides helpers to append, serialize and aggregate geographic locations
 * stored in LocationIQ order (`[longitude, latitude]`).
 *
 * @extends AbstractTypedCollection<LocationVO>
 */
final class LocationVOCollection extends AbstractTypedCollection
{
    public function __construct()
    {
        parent::__construct(LocationVO::class);
    }

    /**
     * Return the raw scalar values of every location in the collection.
     *
     * Each entry is shaped as `[longitude, latitude]`.
     *
     * @return array<int, array{0: float, 1: float}>
     */
    public function toValues(): array
    {
        return $this->map(
            fn (LocationVO $location): array => $location->toArray(),
        )->toArray();
    }

    /**
     * Convert every location VO to a CoordinatesVO.
     */
    public function toCoordinates(): CoordinatesVOCollection
    {
        $collection = new CoordinatesVOCollection;

        foreach ($this as $location) {
            $collection->add($location->toCoordinates());
        }

        return $collection;
    }

    /**
     * Return the locations as `[longitude, latitude]` pairs.
     *
     * @return array<int, array{0: float, 1: float}>
     */
    public function toPairs(): array
    {
        return $this->map(
            fn (LocationVO $location): array => [
                $location->getLongitude(),
                $location->getLatitude(),
            ],
        )->toArray();
    }
}

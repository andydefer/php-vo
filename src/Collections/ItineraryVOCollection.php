<?php

declare(strict_types=1);

namespace AndyDefer\PhpVo\Collections;

use AndyDefer\DomainStructures\Abstracts\AbstractTypedCollection;
use AndyDefer\PhpVo\ValueObjects\ItineraryVO;

/**
 * Collection of ItineraryVO instances.
 *
 * @extends AbstractTypedCollection<ItineraryVO>
 */
final class ItineraryVOCollection extends AbstractTypedCollection
{
    public function __construct()
    {
        parent::__construct(ItineraryVO::class);
    }

    /**
     * Return the total distance of every itinerary.
     *
     * @return array<int, float>
     */
    public function toTotalDistances(): array
    {
        return $this->map(
            fn (ItineraryVO $itinerary): float => $itinerary->totalDistance()->getValue(),
        )->toArray();
    }
}

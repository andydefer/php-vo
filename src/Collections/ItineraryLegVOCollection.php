<?php

declare(strict_types=1);

namespace AndyDefer\PhpVo\Collections;

use AndyDefer\DomainStructures\Abstracts\AbstractTypedCollection;
use AndyDefer\PhpVo\ValueObjects\ItineraryLegVO;

/**
 * Collection of ItineraryLegVO instances.
 *
 * @extends AbstractTypedCollection<ItineraryLegVO>
 */
final class ItineraryLegVOCollection extends AbstractTypedCollection
{
    public function __construct()
    {
        parent::__construct(ItineraryLegVO::class);
    }
}

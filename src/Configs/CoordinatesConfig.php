<?php

declare(strict_types=1);

namespace AndyDefer\PhpVo\Configs;

use AndyDefer\DomainStructures\Abstracts\AbstractConfig;

final class CoordinatesConfig extends AbstractConfig
{
    public function latitudeMin(): float
    {
        return -90.0;
    }

    public function latitudeMax(): float
    {
        return 90.0;
    }

    public function longitudeMin(): float
    {
        return -180.0;
    }

    public function longitudeMax(): float
    {
        return 180.0;
    }

    public function earthRadiusKm(): float
    {
        return 6371.0;
    }

    public function earthRadiusMiles(): float
    {
        return 3959.0;
    }
}

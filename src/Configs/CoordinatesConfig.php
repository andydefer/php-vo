<?php

declare(strict_types=1);

namespace AndyDefer\PhpVo\Configs;

use AndyDefer\PhpVo\Contracts\CoordinatesConfigInterface;
use AndyDefer\PhpVo\ValueObjects\Types\FloatVO;

final class CoordinatesConfig implements CoordinatesConfigInterface
{
    public function latitudeMin(): FloatVO
    {
        return FloatVO::from(-90.0);
    }

    public function latitudeMax(): FloatVO
    {
        return FloatVO::from(90.0);
    }

    public function longitudeMin(): FloatVO
    {
        return FloatVO::from(-180.0);
    }

    public function longitudeMax(): FloatVO
    {
        return FloatVO::from(180.0);
    }

    public function earthRadiusKm(): FloatVO
    {
        return FloatVO::from(6371.0);
    }

    public function earthRadiusMiles(): FloatVO
    {
        return FloatVO::from(3959.0);
    }
}

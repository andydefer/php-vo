<?php

declare(strict_types=1);

namespace AndyDefer\PhpVo\Contracts;

use AndyDefer\PhpVo\ValueObjects\Types\FloatVO;

interface CoordinatesConfigInterface
{
    public function latitudeMin(): FloatVO;

    public function latitudeMax(): FloatVO;

    public function longitudeMin(): FloatVO;

    public function longitudeMax(): FloatVO;

    public function earthRadiusKm(): FloatVO;

    public function earthRadiusMiles(): FloatVO;
}

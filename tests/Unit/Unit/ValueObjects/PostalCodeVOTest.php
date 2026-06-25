<?php

declare(strict_types=1);

namespace AndyDefer\DomainStructures\Tests\Unit\ValueObjects;

use AndyDefer\PhpVo\Tests\UnitTestCase;
use AndyDefer\PhpVo\ValueObjects\PostalCodeVO;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\AllowMockObjectsWithoutExpectations;

#[AllowMockObjectsWithoutExpectations]
final class PostalCodeVOTest extends UnitTestCase
{
    public function test_create_valid_postal_code(): void
    {
        $postalCode = PostalCodeVO::from('75001');

        $this->assertSame('75001', $postalCode->getValue());
    }

    public function test_create_postal_code_with_spaces_is_trimmed(): void
    {
        $postalCode = PostalCodeVO::from(' 75001 ');

        $this->assertSame('75001', $postalCode->getValue());
    }

    public function test_create_postal_code_with_letters_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid postal code format: "ABCDE". Must be 5 digits.');

        PostalCodeVO::from('ABCDE');
    }

    public function test_create_postal_code_with_4_digits_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid postal code format: "7500". Must be 5 digits.');

        PostalCodeVO::from('7500');
    }

    public function test_create_postal_code_with_6_digits_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid postal code format: "750001". Must be 5 digits.');

        PostalCodeVO::from('750001');
    }

    public function test_create_postal_code_from_integer_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Postal code must be a string');

        PostalCodeVO::from(75001);
    }

    public function test_create_postal_code_from_array_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Postal code must be a string');

        PostalCodeVO::from(['75001']);
    }

    public function test_create_postal_code_from_null_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Postal code must be a string');

        PostalCodeVO::from(null);
    }

    public function test_create_postal_code_from_existing_postal_code_returns_same_instance(): void
    {
        $original = PostalCodeVO::from('75001');
        $duplicate = PostalCodeVO::from($original);

        $this->assertSame($original, $duplicate);
    }
}

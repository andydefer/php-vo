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
        $postalCode = new PostalCodeVO('75001');

        $this->assertSame('75001', $postalCode->getValue());
    }

    public function test_create_postal_code_with_10_characters(): void
    {
        $postalCode = new PostalCodeVO('ABC-123-DE');

        $this->assertSame('ABC-123-DE', $postalCode->getValue());
    }

    public function test_create_postal_code_with_spaces(): void
    {
        $postalCode = new PostalCodeVO('75001 123');

        $this->assertSame('75001 123', $postalCode->getValue());
    }

    public function test_create_postal_code_with_11_characters_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Maximum 10 characters allowed');

        new PostalCodeVO('12345678901');
    }

    public function test_create_postal_code_with_special_characters_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Only alphanumeric characters, spaces and hyphens are allowed');

        new PostalCodeVO('75001!@#');
    }

    public function test_create_postal_code_with_letters_is_valid(): void
    {
        $postalCode = new PostalCodeVO('ABC12');

        $this->assertSame('ABC12', $postalCode->getValue());
    }

    public function test_create_postal_code_with_hyphens_is_valid(): void
    {
        $postalCode = new PostalCodeVO('ABC-123');

        $this->assertSame('ABC-123', $postalCode->getValue());
    }

    public function test_create_postal_code_with_alphanumeric_and_hyphen_is_valid(): void
    {
        $postalCode = new PostalCodeVO('A1B-2C3');

        $this->assertSame('A1B-2C3', $postalCode->getValue());
    }

    public function test_create_postal_code_with_empty_string_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Only alphanumeric characters, spaces and hyphens are allowed');

        new PostalCodeVO('');
    }

    public function test_create_postal_code_from_existing_postal_code_returns_same_instance(): void
    {
        $original = new PostalCodeVO('75001');
        $duplicate = PostalCodeVO::from($original);

        $this->assertSame($original, $duplicate);
    }
}

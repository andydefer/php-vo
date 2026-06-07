<?php

declare(strict_types=1);

namespace AndyDefer\PhpVo\Tests\Unit\ValueObjects;

use AndyDefer\PhpVo\ValueObjects\CoordinatesVO;
use AndyDefer\PhpVo\ValueObjects\IdVO;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class IdVOTest extends TestCase
{
    public function test_create_valid_id(): void
    {
        $id = IdVO::from(123);

        $this->assertSame(123, $id->getValue());
    }

    public function test_create_id_minimum_valid(): void
    {
        $id = IdVO::from(1);

        $this->assertSame(1, $id->getValue());
    }

    public function test_create_id_zero_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('ID must be positive, got: 0');

        IdVO::from(0);
    }

    public function test_create_id_negative_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('ID must be positive, got: -5');

        IdVO::from(-5);
    }

    public function test_get_value_returns_int(): void
    {
        $id = IdVO::from(123);

        $this->assertIsInt($id->getValue());
        $this->assertSame(123, $id->getValue());
    }

    public function test_to_readable_string(): void
    {
        $id = IdVO::from(123);

        $this->assertSame('ID #123', $id->toReadableString());
    }

    public function test_to_zero_padded(): void
    {
        $id = IdVO::from(123);

        $this->assertSame('00123', $id->toZeroPadded(5));
    }

    public function test_to_zero_padded_with_length_less_than_digits(): void
    {
        $id = IdVO::from(12345);

        $this->assertSame('12345', $id->toZeroPadded(3));
    }

    public function test_is_valid_returns_true(): void
    {
        $id = IdVO::from(123);

        $this->assertTrue($id->isValid());
    }

    public function test_increment(): void
    {
        $id = IdVO::from(123);
        $incremented = $id->increment();

        $this->assertNotSame($id, $incremented);
        $this->assertSame(124, $incremented->getValue());
        $this->assertSame(123, $id->getValue());
    }

    public function test_decrement(): void
    {
        $id = IdVO::from(123);
        $decremented = $id->decrement();

        $this->assertNotSame($id, $decremented);
        $this->assertSame(122, $decremented->getValue());
        $this->assertSame(123, $id->getValue());
    }

    public function test_decrement_to_zero_throws_exception(): void
    {
        $id = IdVO::from(1);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('ID must be positive, got: 0');

        $id->decrement();
    }

    public function test_create_from_existing_id_returns_same_instance(): void
    {
        $original = IdVO::from(123);
        $duplicate = IdVO::from($original);

        $this->assertSame($original, $duplicate);
    }

    public function test_equals_returns_true_for_same_id(): void
    {
        $id1 = IdVO::from(123);
        $id2 = IdVO::from(123);

        $this->assertTrue($id1->equals($id2));
    }

    public function test_equals_returns_false_for_different_id(): void
    {
        $id1 = IdVO::from(123);
        $id2 = IdVO::from(456);

        $this->assertFalse($id1->equals($id2));
    }

    public function test_to_string_magic_method(): void
    {
        $id = IdVO::from(123);

        $this->assertSame('123', (string)$id);
    }

    public function test_handles_large_id(): void
    {
        $id = IdVO::from(PHP_INT_MAX);

        $this->assertSame(PHP_INT_MAX, $id->getValue());
    }

    public function test_chaining_increment_and_decrement(): void
    {
        $id = IdVO::from(10);

        $result = $id->increment()->increment()->decrement();

        $this->assertSame(11, $result->getValue());
    }
}

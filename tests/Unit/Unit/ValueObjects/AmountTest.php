<?php

declare(strict_types=1);

namespace AndyDefer\PhpVo\Tests\Unit\ValueObjects;

use AndyDefer\PhpVo\ValueObjects\Amount;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class AmountTest extends TestCase
{
    public function test_create_amount_from_string(): void
    {
        $amount = Amount::from('99.99');
        $this->assertSame(99.99, $amount->getValue());
        $this->assertSame('99.99', $amount->toString());
    }

    public function test_create_amount_from_int(): void
    {
        $amount = Amount::from(100);
        $this->assertSame(100.0, $amount->getValue());
        $this->assertSame('100.00', $amount->toString());
    }

    public function test_create_amount_from_float(): void
    {
        $amount = Amount::from(99.99);
        $this->assertSame(99.99, $amount->getValue());
        $this->assertSame('99.99', $amount->toString());
    }

    public function test_create_amount_from_float_with_more_decimals_rounds(): void
    {
        $amount = Amount::from(99.999);
        $this->assertSame(100.0, $amount->getValue());
        $this->assertSame('100.00', $amount->toString());
    }

    public function test_create_amount_from_string_with_comma(): void
    {
        $amount = Amount::from('99,99');
        $this->assertSame(99.99, $amount->getValue());
        $this->assertSame('99.99', $amount->toString());
    }

    public function test_create_amount_from_string_with_negative_value(): void
    {
        $amount = Amount::from('-99.99');
        $this->assertSame(-99.99, $amount->getValue());
        $this->assertSame('-99.99', $amount->toString());
    }

    public function test_create_amount_from_string_with_invalid_format_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid amount format:');

        Amount::from('abc');
    }

    public function test_create_amount_from_string_with_wrong_decimal_places_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid amount format: 99.999');

        Amount::from('99.999');
    }

    public function test_create_amount_from_existing_amount_returns_same_instance(): void
    {
        $original = Amount::from('99.99');
        $duplicate = Amount::from($original);

        $this->assertSame($original, $duplicate);
    }

    public function test_get_value_returns_float(): void
    {
        $amount = Amount::from('99.99');
        $this->assertIsFloat($amount->getValue());
        $this->assertSame(99.99, $amount->getValue());
    }

    public function test_to_string_returns_string(): void
    {
        $amount = Amount::from('99.99');
        $this->assertIsString($amount->toString());
        $this->assertSame('99.99', $amount->toString());
    }

    public function test_to_float_returns_float(): void
    {
        $amount = Amount::from('99.99');
        $this->assertIsFloat($amount->toFloat());
        $this->assertSame(99.99, $amount->toFloat());
    }

    public function test_to_int_returns_cents(): void
    {
        $amount = Amount::from('99.99');
        $this->assertIsInt($amount->toInt());
        $this->assertSame(9999, $amount->toInt());
    }

    public function test_add(): void
    {
        $amount1 = Amount::from('50.50');
        $amount2 = Amount::from('25.25');
        $result = $amount1->add($amount2);

        $this->assertSame('75.75', $result->toString());
        $this->assertSame(75.75, $result->getValue());
    }

    public function test_subtract(): void
    {
        $amount1 = Amount::from('100.00');
        $amount2 = Amount::from('30.30');
        $result = $amount1->subtract($amount2);

        $this->assertSame('69.70', $result->toString());
        $this->assertSame(69.70, $result->getValue());
    }

    public function test_multiply(): void
    {
        $amount = Amount::from('10.50');
        $result = $amount->multiply(2.5);

        $this->assertSame('26.25', $result->toString());
        $this->assertSame(26.25, $result->getValue());
    }

    public function test_multiply_with_string(): void
    {
        $amount = Amount::from('10.50');
        $result = $amount->multiply('2.5');

        $this->assertSame('26.25', $result->toString());
        $this->assertSame(26.25, $result->getValue());
    }

    public function test_divide(): void
    {
        $amount = Amount::from('100.00');
        $result = $amount->divide(4);

        $this->assertSame('25.00', $result->toString());
        $this->assertSame(25.00, $result->getValue());
    }

    public function test_divide_by_zero_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Division by zero');

        $amount = Amount::from('100.00');
        $amount->divide(0);
    }

    public function test_percentage(): void
    {
        $amount = Amount::from('200.00');
        $result = $amount->percentage(15);

        $this->assertSame('30.00', $result->toString());
        $this->assertSame(30.00, $result->getValue());
    }

    public function test_percentage_with_float(): void
    {
        $amount = Amount::from('200.00');
        $result = $amount->percentage(15.5);

        $this->assertSame('31.00', $result->toString());
        $this->assertSame(31.00, $result->getValue());
    }

    public function test_is_zero(): void
    {
        $zero = Amount::from('0.00');
        $positive = Amount::from('10.00');
        $negative = Amount::from('-10.00');

        $this->assertTrue($zero->isZero());
        $this->assertFalse($positive->isZero());
        $this->assertFalse($negative->isZero());
    }

    public function test_is_positive(): void
    {
        $positive = Amount::from('10.00');
        $negative = Amount::from('-10.00');
        $zero = Amount::from('0.00');

        $this->assertTrue($positive->isPositive());
        $this->assertFalse($negative->isPositive());
        $this->assertFalse($zero->isPositive());
    }

    public function test_is_negative(): void
    {
        $positive = Amount::from('10.00');
        $negative = Amount::from('-10.00');
        $zero = Amount::from('0.00');

        $this->assertFalse($positive->isNegative());
        $this->assertTrue($negative->isNegative());
        $this->assertFalse($zero->isNegative());
    }

    public function test_immutability(): void
    {
        $original = Amount::from('100.00');
        $new = $original->add(Amount::from('50.00'));

        $this->assertNotSame($original, $new);
        $this->assertSame('100.00', $original->toString());
        $this->assertSame('150.00', $new->toString());
    }

    public function test_chaining_operations(): void
    {
        $result = Amount::from('100.00')
            ->add(Amount::from('50.00'))
            ->subtract(Amount::from('30.00'))
            ->multiply(2)
            ->percentage(10);

        $this->assertSame('24.00', $result->toString());
        $this->assertSame(24.00, $result->getValue());
    }

    public function test_to_string_magic_method(): void
    {
        $amount = Amount::from('99.99');
        $this->assertSame('99.99', (string)$amount);
    }

    public function test_zero_amount_is_valid(): void
    {
        $amount = Amount::from('0.00');
        $this->assertSame(0.0, $amount->getValue());
        $this->assertTrue($amount->isZero());
    }

    public function test_amount_without_decimals_normalizes(): void
    {
        $amount = Amount::from('100');
        $this->assertSame('100.00', $amount->toString());
        $this->assertSame(100.0, $amount->getValue());
    }
}

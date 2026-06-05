<?php

declare(strict_types=1);

namespace AndyDefer\PhpVo\Tests\Unit\ValueObjects;

use AndyDefer\PhpVo\ValueObjects\AmountVO;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class AmountVOTest extends TestCase
{
    public function test_create_amount_from_string(): void
    {
        // Arrange & Act: Create amount from string
        $amount = AmountVO::from('99.99');

        // Assert: Values are correctly parsed
        $this->assertSame(99.99, $amount->getValue());
        $this->assertSame('99.99', $amount->toString());
    }

    public function test_create_amount_from_int(): void
    {
        // Arrange & Act: Create amount from integer
        $amount = AmountVO::from(100);

        // Assert: Integer is converted to float with 2 decimals
        $this->assertSame(100.0, $amount->getValue());
        $this->assertSame('100.00', $amount->toString());
    }

    public function test_create_amount_from_float(): void
    {
        // Arrange & Act: Create amount from float
        $amount = AmountVO::from(99.99);

        // Assert: Float is preserved with 2 decimals
        $this->assertSame(99.99, $amount->getValue());
        $this->assertSame('99.99', $amount->toString());
    }

    public function test_create_amount_from_float_with_more_decimals_rounds(): void
    {
        // Arrange & Act: Create amount from float with 3 decimals
        $amount = AmountVO::from(99.999);

        // Assert: Value is rounded to 2 decimals
        $this->assertSame(100.0, $amount->getValue());
        $this->assertSame('100.00', $amount->toString());
    }

    public function test_create_amount_from_string_with_comma(): void
    {
        // Arrange & Act: Create amount with comma as decimal separator
        $amount = AmountVO::from('99,99');

        // Assert: Comma is normalized to dot
        $this->assertSame(99.99, $amount->getValue());
        $this->assertSame('99.99', $amount->toString());
    }

    public function test_create_amount_from_string_with_negative_value(): void
    {
        // Arrange & Act: Create negative amount
        $amount = AmountVO::from('-99.99');

        // Assert: Negative sign is preserved
        $this->assertSame(-99.99, $amount->getValue());
        $this->assertSame('-99.99', $amount->toString());
    }

    public function test_create_amount_from_string_with_invalid_format_throws_exception(): void
    {
        // Assert: Exception is expected for invalid format
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid amount format:');

        // Act: Attempt to create amount from invalid string
        AmountVO::from('abc');
    }

    public function test_create_amount_from_string_with_wrong_decimal_places_throws_exception(): void
    {
        // Assert: Exception for more than 2 decimal places
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid amount format: 99.999');

        // Act: Attempt to create amount with 3 decimals in string
        AmountVO::from('99.999');
    }

    public function test_create_amount_from_existing_amount_returns_same_instance(): void
    {
        // Arrange: Create original amount
        $original = AmountVO::from('99.99');

        // Act: Create duplicate from existing instance
        $duplicate = AmountVO::from($original);

        // Assert: Same instance is returned (identity preservation)
        $this->assertSame($original, $duplicate);
    }

    public function test_get_value_returns_float(): void
    {
        // Arrange: Create amount
        $amount = AmountVO::from('99.99');

        // Act & Assert: getValue returns float type
        $this->assertIsFloat($amount->getValue());
        $this->assertSame(99.99, $amount->getValue());
    }

    public function test_to_string_returns_string(): void
    {
        // Arrange: Create amount
        $amount = AmountVO::from('99.99');

        // Act & Assert: toString returns string type
        $this->assertIsString($amount->toString());
        $this->assertSame('99.99', $amount->toString());
    }

    public function test_to_float_returns_float(): void
    {
        // Arrange: Create amount
        $amount = AmountVO::from('99.99');

        // Act & Assert: toFloat returns float
        $this->assertIsFloat($amount->toFloat());
        $this->assertSame(99.99, $amount->toFloat());
    }

    public function test_to_int_returns_cents(): void
    {
        // Arrange: Create amount
        $amount = AmountVO::from('99.99');

        // Act & Assert: toInt returns cents (amount * 100)
        $this->assertIsInt($amount->toInt());
        $this->assertSame(9999, $amount->toInt());
    }

    public function test_add(): void
    {
        // Arrange: Create two amounts
        $amount1 = AmountVO::from('50.50');
        $amount2 = AmountVO::from('25.25');

        // Act: Add them together
        $result = $amount1->add($amount2);

        // Assert: Sum is correct
        $this->assertSame('75.75', $result->toString());
        $this->assertSame(75.75, $result->getValue());
    }

    public function test_subtract(): void
    {
        // Arrange: Create two amounts
        $amount1 = AmountVO::from('100.00');
        $amount2 = AmountVO::from('30.30');

        // Act: Subtract second from first
        $result = $amount1->subtract($amount2);

        // Assert: Difference is correct
        $this->assertSame('69.70', $result->toString());
        $this->assertSame(69.70, $result->getValue());
    }

    public function test_multiply(): void
    {
        // Arrange: Create amount
        $amount = AmountVO::from('10.50');

        // Act: Multiply by float
        $result = $amount->multiply(2.5);

        // Assert: Product is correct
        $this->assertSame('26.25', $result->toString());
        $this->assertSame(26.25, $result->getValue());
    }

    public function test_multiply_with_string(): void
    {
        // Arrange: Create amount
        $amount = AmountVO::from('10.50');

        // Act: Multiply by string
        $result = $amount->multiply('2.5');

        // Assert: Product is correct (string multiplier works)
        $this->assertSame('26.25', $result->toString());
        $this->assertSame(26.25, $result->getValue());
    }

    public function test_divide(): void
    {
        // Arrange: Create amount
        $amount = AmountVO::from('100.00');

        // Act: Divide by integer
        $result = $amount->divide(4);

        // Assert: Quotient is correct
        $this->assertSame('25.00', $result->toString());
        $this->assertSame(25.00, $result->getValue());
    }

    public function test_divide_by_zero_throws_exception(): void
    {
        // Arrange: Create amount
        $amount = AmountVO::from('100.00');

        // Assert: Exception for division by zero
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Division by zero');

        // Act: Attempt to divide by zero
        $amount->divide(0);
    }

    public function test_percentage(): void
    {
        // Arrange: Create amount
        $amount = AmountVO::from('200.00');

        // Act: Calculate 15%
        $result = $amount->percentage(15);

        // Assert: Percentage is correct
        $this->assertSame('30.00', $result->toString());
        $this->assertSame(30.00, $result->getValue());
    }

    public function test_percentage_with_float(): void
    {
        // Arrange: Create amount
        $amount = AmountVO::from('200.00');

        // Act: Calculate 15.5%
        $result = $amount->percentage(15.5);

        // Assert: Percentage with decimal is correct
        $this->assertSame('31.00', $result->toString());
        $this->assertSame(31.00, $result->getValue());
    }

    public function test_is_zero(): void
    {
        // Arrange: Create zero, positive, and negative amounts
        $zero = AmountVO::from('0.00');
        $positive = AmountVO::from('10.00');
        $negative = AmountVO::from('-10.00');

        // Assert: Only zero returns true
        $this->assertTrue($zero->isZero());
        $this->assertFalse($positive->isZero());
        $this->assertFalse($negative->isZero());
    }

    public function test_is_positive(): void
    {
        // Arrange: Create amounts
        $positive = AmountVO::from('10.00');
        $negative = AmountVO::from('-10.00');
        $zero = AmountVO::from('0.00');

        // Assert: Only positive returns true
        $this->assertTrue($positive->isPositive());
        $this->assertFalse($negative->isPositive());
        $this->assertFalse($zero->isPositive());
    }

    public function test_is_negative(): void
    {
        // Arrange: Create amounts
        $positive = AmountVO::from('10.00');
        $negative = AmountVO::from('-10.00');
        $zero = AmountVO::from('0.00');

        // Assert: Only negative returns true
        $this->assertFalse($positive->isNegative());
        $this->assertTrue($negative->isNegative());
        $this->assertFalse($zero->isNegative());
    }

    public function test_immutability(): void
    {
        // Arrange: Create original amount
        $original = AmountVO::from('100.00');

        // Act: Perform operation creating new instance
        $new = $original->add(AmountVO::from('50.00'));

        // Assert: Original remains unchanged, new instance is different
        $this->assertNotSame($original, $new);
        $this->assertSame('100.00', $original->toString());
        $this->assertSame('150.00', $new->toString());
    }

    public function test_chaining_operations(): void
    {
        // Act: Chain multiple operations
        $result = AmountVO::from('100.00')
            ->add(AmountVO::from('50.00'))
            ->subtract(AmountVO::from('30.00'))
            ->multiply(2)
            ->percentage(10);

        // Assert: Final result after chain is correct
        // (100 + 50 - 30) = 120 * 2 = 240 * 10% = 24
        $this->assertSame('24.00', $result->toString());
        $this->assertSame(24.00, $result->getValue());
    }

    public function test_to_string_magic_method(): void
    {
        // Arrange: Create amount
        $amount = AmountVO::from('99.99');

        // Assert: String casting returns formatted value
        $this->assertSame('99.99', (string)$amount);
    }

    public function test_zero_amount_is_valid(): void
    {
        // Arrange & Act: Create zero amount
        $amount = AmountVO::from('0.00');

        // Assert: Zero is accepted
        $this->assertSame(0.0, $amount->getValue());
        $this->assertTrue($amount->isZero());
    }

    public function test_amount_without_decimals_normalizes(): void
    {
        // Arrange & Act: Create amount without decimal places
        $amount = AmountVO::from('100');

        // Assert: Two decimals are added
        $this->assertSame('100.00', $amount->toString());
        $this->assertSame(100.0, $amount->getValue());
    }
}

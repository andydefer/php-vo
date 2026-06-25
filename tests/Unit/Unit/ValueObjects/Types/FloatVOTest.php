<?php

declare(strict_types=1);

namespace AndyDefer\PhpVo\Tests\Unit\ValueObjects\Types;

use AndyDefer\PhpVo\ValueObjects\Types\BoolVO;
use AndyDefer\PhpVo\ValueObjects\Types\FloatVO;
use AndyDefer\PhpVo\ValueObjects\Types\StringVO;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class FloatVOTest extends TestCase
{
    // ========== TESTS DE CRÉATION ==========

    public function test_create_float_vo(): void
    {
        $float = FloatVO::from(42.5);
        $this->assertSame(42.5, $float->getValue());
    }

    public function test_create_from_integer(): void
    {
        $float = FloatVO::from(42);
        $this->assertSame(42.0, $float->getValue());
    }

    public function test_create_from_string(): void
    {
        $float = FloatVO::from('123.45');
        $this->assertSame(123.45, $float->getValue());
    }

    public function test_create_from_string_vo(): void
    {
        $float = FloatVO::from(StringVO::from('123.45'));
        $this->assertSame(123.45, $float->getValue());
    }

    public function test_create_from_bool_vo_true(): void
    {
        $float = FloatVO::from(BoolVO::from(true));
        $this->assertSame(1.0, $float->getValue());
    }

    public function test_create_from_bool_vo_false(): void
    {
        $float = FloatVO::from(BoolVO::from(false));
        $this->assertSame(0.0, $float->getValue());
    }

    public function test_create_from_invalid_string_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot convert value to float for parameter $value');
        FloatVO::from('abc');
    }

    public function test_create_from_existing_vo_returns_same_instance(): void
    {
        $original = FloatVO::from(123.45);
        $duplicate = FloatVO::from($original);
        $this->assertSame($original, $duplicate);
    }

    // ========== TESTS DES OPÉRATIONS ARITHMÉTIQUES ==========

    public function test_add_returns_float_vo(): void
    {
        $float = FloatVO::from(10.5);
        $result = $float->add(5.5);
        $this->assertInstanceOf(FloatVO::class, $result);
        $this->assertEquals(16.0, $result->getValue());
    }

    public function test_add_with_int(): void
    {
        $float = FloatVO::from(10.5);
        $result = $float->add(5);
        $this->assertEquals(15.5, $result->getValue());
    }

    public function test_add_with_string(): void
    {
        $float = FloatVO::from(10.5);
        $result = $float->add('5.5');
        $this->assertEquals(16.0, $result->getValue());
    }

    public function test_subtract_returns_float_vo(): void
    {
        $float = FloatVO::from(10.5);
        $result = $float->subtract(5.5);
        $this->assertEquals(5.0, $result->getValue());
    }

    public function test_subtract_with_int(): void
    {
        $float = FloatVO::from(10.5);
        $result = $float->subtract(5);
        $this->assertEquals(5.5, $result->getValue());
    }

    public function test_multiply_returns_float_vo(): void
    {
        $float = FloatVO::from(10.5);
        $result = $float->multiply(2.0);
        $this->assertEquals(21.0, $result->getValue());
    }

    public function test_multiply_with_int(): void
    {
        $float = FloatVO::from(10.5);
        $result = $float->multiply(2);
        $this->assertEquals(21.0, $result->getValue());
    }

    public function test_divide_returns_float_vo(): void
    {
        $float = FloatVO::from(10.5);
        $result = $float->divide(2.0);
        $this->assertEquals(5.25, $result->getValue());
    }

    public function test_divide_with_int(): void
    {
        $float = FloatVO::from(10.5);
        $result = $float->divide(2);
        $this->assertEquals(5.25, $result->getValue());
    }

    public function test_divide_by_zero_throws_exception(): void
    {
        $float = FloatVO::from(10.5);
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Division by zero is not allowed');
        $float->divide(0);
    }

    public function test_mod_returns_float_vo(): void
    {
        $float = FloatVO::from(10.5);
        $result = $float->mod(3.0);
        $this->assertEquals(1.5, $result->getValue());
    }

    public function test_mod_with_int(): void
    {
        $float = FloatVO::from(10.5);
        $result = $float->mod(3);
        $this->assertEquals(1.5, $result->getValue());
    }

    public function test_mod_by_zero_throws_exception(): void
    {
        $float = FloatVO::from(10.5);
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Modulo by zero is not allowed');
        $float->mod(0);
    }

    public function test_pow_returns_float_vo(): void
    {
        $float = FloatVO::from(2.0);
        $result = $float->pow(3.0);
        $this->assertEquals(8.0, $result->getValue());
    }

    public function test_pow_with_int_exponent(): void
    {
        $float = FloatVO::from(2.0);
        $result = $float->pow(3);
        $this->assertEquals(8.0, $result->getValue());
    }

    public function test_pow_with_float_exponent(): void
    {
        $float = FloatVO::from(4.0);
        $result = $float->pow(0.5);
        $this->assertEquals(2.0, $result->getValue());
    }

    // ========== TESTS D'INCRÉMENTATION/DÉCRÉMENTATION ==========

    public function test_increment_with_default_value(): void
    {
        $float = FloatVO::from(5.5);
        $result = $float->increment();
        $this->assertEquals(6.5, $result->getValue());
    }

    public function test_increment_with_custom_value(): void
    {
        $float = FloatVO::from(5.5);
        $result = $float->increment(3.5);
        $this->assertEquals(9.0, $result->getValue());
    }

    public function test_increment_with_int(): void
    {
        $float = FloatVO::from(5.5);
        $result = $float->increment(3);
        $this->assertEquals(8.5, $result->getValue());
    }

    public function test_decrement_with_default_value(): void
    {
        $float = FloatVO::from(5.5);
        $result = $float->decrement();
        $this->assertEquals(4.5, $result->getValue());
    }

    public function test_decrement_with_custom_value(): void
    {
        $float = FloatVO::from(5.5);
        $result = $float->decrement(3.5);
        $this->assertEquals(2.0, $result->getValue());
    }

    // ========== TESTS DES OPÉRATIONS MATHÉMATIQUES AVANCÉES ==========

    public function test_abs_returns_absolute_value(): void
    {
        $float = FloatVO::from(-5.5);
        $result = $float->abs();
        $this->assertEquals(5.5, $result->getValue());
    }

    public function test_abs_positive(): void
    {
        $float = FloatVO::from(5.5);
        $result = $float->abs();
        $this->assertEquals(5.5, $result->getValue());
    }

    public function test_negate_returns_negated_value(): void
    {
        $float = FloatVO::from(5.5);
        $result = $float->negate();
        $this->assertEquals(-5.5, $result->getValue());
    }

    public function test_sqrt_returns_float_vo(): void
    {
        $float = FloatVO::from(16.0);
        $result = $float->sqrt();
        $this->assertEquals(4.0, $result->getValue());
    }

    public function test_sqrt_with_int(): void
    {
        $float = FloatVO::from(16);
        $result = $float->sqrt();
        $this->assertEquals(4.0, $result->getValue());
    }

    public function test_sqrt_throws_exception_for_negative(): void
    {
        $float = FloatVO::from(-16.0);
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Square root of negative number is not defined');
        $float->sqrt();
    }

    public function test_log_returns_float_vo(): void
    {
        $float = FloatVO::from(exp(1));
        $result = $float->log();
        $this->assertEquals(1.0, round($result->getValue(), 10));
    }

    public function test_log_throws_exception_for_non_positive(): void
    {
        $float = FloatVO::from(-10.0);
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Logarithm of non-positive number is not defined');
        $float->log();
    }

    public function test_log10_returns_float_vo(): void
    {
        $float = FloatVO::from(100.0);
        $result = $float->log10();
        $this->assertEquals(2.0, $result->getValue());
    }

    public function test_log_base_returns_float_vo(): void
    {
        $float = FloatVO::from(8.0);
        $result = $float->logBase(2.0);
        $this->assertEquals(3.0, $result->getValue());
    }

    public function test_log_base_with_int(): void
    {
        $float = FloatVO::from(8.0);
        $result = $float->logBase(2);
        $this->assertEquals(3.0, $result->getValue());
    }

    public function test_log_base_throws_exception_for_invalid_base(): void
    {
        $float = FloatVO::from(8.0);
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid logarithm parameters');
        $float->logBase(1);
    }

    public function test_exp_returns_float_vo(): void
    {
        $float = FloatVO::from(1.0);
        $result = $float->exp();
        $this->assertEquals(exp(1), $result->getValue());
    }

    // ========== TESTS DES FONCTIONS TRIGONOMÉTRIQUES ==========

    public function test_sin_returns_float_vo(): void
    {
        $float = FloatVO::from(0);
        $result = $float->sin();
        $this->assertEquals(0.0, $result->getValue());
    }

    public function test_cos_returns_float_vo(): void
    {
        $float = FloatVO::from(0);
        $result = $float->cos();
        $this->assertEquals(1.0, $result->getValue());
    }

    public function test_tan_returns_float_vo(): void
    {
        $float = FloatVO::from(0);
        $result = $float->tan();
        $this->assertEquals(0.0, $result->getValue());
    }

    public function test_asin_returns_float_vo(): void
    {
        $float = FloatVO::from(0.5);
        $result = $float->asin();
        $this->assertEquals(asin(0.5), $result->getValue());
    }

    public function test_asin_throws_exception_when_out_of_range(): void
    {
        $float = FloatVO::from(2.0);
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Arcsin argument must be between -1 and 1');
        $float->asin();
    }

    public function test_acos_returns_float_vo(): void
    {
        $float = FloatVO::from(0.5);
        $result = $float->acos();
        $this->assertEquals(acos(0.5), $result->getValue());
    }

    public function test_acos_throws_exception_when_out_of_range(): void
    {
        $float = FloatVO::from(2.0);
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Arccos argument must be between -1 and 1');
        $float->acos();
    }

    public function test_atan_returns_float_vo(): void
    {
        $float = FloatVO::from(1.0);
        $result = $float->atan();
        $this->assertEquals(atan(1.0), $result->getValue());
    }

    public function test_atan2_returns_float_vo(): void
    {
        $float = FloatVO::from(1.0);
        $result = $float->atan2(1.0);
        $this->assertEquals(atan2(1.0, 1.0), $result->getValue());
    }

    public function test_atan2_with_int(): void
    {
        $float = FloatVO::from(1.0);
        $result = $float->atan2(1);
        $this->assertEquals(atan2(1.0, 1.0), $result->getValue());
    }

    // ========== TESTS DE CONVERSION D'ANGLE ==========

    public function test_deg2rad_returns_float_vo(): void
    {
        $float = FloatVO::from(180.0);
        $result = $float->deg2rad();
        $this->assertEquals(M_PI, $result->getValue());
    }

    public function test_rad2deg_returns_float_vo(): void
    {
        $float = FloatVO::from(M_PI);
        $result = $float->rad2deg();
        $this->assertEquals(180.0, $result->getValue());
    }

    // ========== TESTS DES FONCTIONS HYPERBOLIQUES ==========

    public function test_sinh_returns_float_vo(): void
    {
        $float = FloatVO::from(0);
        $result = $float->sinh();
        $this->assertEquals(0.0, $result->getValue());
    }

    public function test_cosh_returns_float_vo(): void
    {
        $float = FloatVO::from(0);
        $result = $float->cosh();
        $this->assertEquals(1.0, $result->getValue());
    }

    public function test_tanh_returns_float_vo(): void
    {
        $float = FloatVO::from(0);
        $result = $float->tanh();
        $this->assertEquals(0.0, $result->getValue());
    }

    // ========== TESTS D'ARRONDI ==========

    public function test_round_returns_float_vo(): void
    {
        $float = FloatVO::from(3.14159);
        $result = $float->round(2);
        $this->assertEquals(3.14, $result->getValue());
    }

    public function test_round_with_int_precision(): void
    {
        $float = FloatVO::from(3.14159);
        $result = $float->round(2);
        $this->assertEquals(3.14, $result->getValue());
    }

    public function test_round_with_string_precision(): void
    {
        $float = FloatVO::from(3.14159);
        $result = $float->round('2');
        $this->assertEquals(3.14, $result->getValue());
    }

    public function test_round_without_precision(): void
    {
        $float = FloatVO::from(3.5);
        $result = $float->round();
        $this->assertEquals(4.0, $result->getValue());
    }

    public function test_ceil_returns_float_vo(): void
    {
        $float = FloatVO::from(3.14);
        $result = $float->ceil();
        $this->assertEquals(4.0, $result->getValue());
    }

    public function test_ceil_negative(): void
    {
        $float = FloatVO::from(-3.14);
        $result = $float->ceil();
        $this->assertEquals(-3.0, $result->getValue());
    }

    public function test_floor_returns_float_vo(): void
    {
        $float = FloatVO::from(3.14);
        $result = $float->floor();
        $this->assertEquals(3.0, $result->getValue());
    }

    public function test_floor_negative(): void
    {
        $float = FloatVO::from(-3.14);
        $result = $float->floor();
        $this->assertEquals(-4.0, $result->getValue());
    }

    // ========== TESTS DE FACTORIELLE ==========

    public function test_factorial(): void
    {
        $float = FloatVO::from(5);
        $result = $float->factorial();
        $this->assertEquals(120.0, $result->getValue());
    }

    public function test_factorial_with_float(): void
    {
        $float = FloatVO::from(5.0);
        $result = $float->factorial();
        $this->assertEquals(120.0, $result->getValue());
    }

    public function test_factorial_throws_exception_for_negative(): void
    {
        $float = FloatVO::from(-5);
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Factorial is only defined for non-negative integers');
        $float->factorial();
    }

    public function test_factorial_throws_exception_for_non_integer(): void
    {
        $float = FloatVO::from(5.5);
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Factorial is only defined for non-negative integers');
        $float->factorial();
    }

    // ========== TESTS DES OPÉRATIONS BIT À BIT ==========

    public function test_bitwise_and(): void
    {
        $float = FloatVO::from(10);
        $result = $float->bitwiseAnd(7);
        $this->assertEquals(2, $result->getValue());
    }

    public function test_bitwise_or(): void
    {
        $float = FloatVO::from(10);
        $result = $float->bitwiseOr(3);
        $this->assertEquals(11, $result->getValue());
    }

    public function test_bitwise_xor(): void
    {
        $float = FloatVO::from(10);
        $result = $float->bitwiseXor(3);
        $this->assertEquals(9, $result->getValue());
    }

    public function test_bitwise_not(): void
    {
        $float = FloatVO::from(10);
        $result = $float->bitwiseNot();
        $this->assertEquals(~10, $result->getValue());
    }

    public function test_shift_left(): void
    {
        $float = FloatVO::from(5);
        $result = $float->shiftLeft(2);
        $this->assertEquals(20, $result->getValue());
    }

    public function test_shift_right(): void
    {
        $float = FloatVO::from(20);
        $result = $float->shiftRight(2);
        $this->assertEquals(5, $result->getValue());
    }

    public function test_bitwise_throws_exception_for_non_integer(): void
    {
        $float = FloatVO::from(5.5);
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Bitwise operations only work on integers');
        $float->bitwiseAnd(7);
    }

    // ========== TESTS DES DIVISEURS ET FACTEURS ==========

    public function test_get_divisors(): void
    {
        $float = FloatVO::from(12);
        $divisors = $float->getDivisors();
        $values = array_map(fn ($d) => $d->getValue(), $divisors->toArray());
        $this->assertEquals([1, 2, 3, 4, 6, 12], $values);
    }

    public function test_get_divisors_throws_for_non_integer(): void
    {
        $float = FloatVO::from(12.5);
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Bitwise operations only work on integers');
        $float->getDivisors();
    }

    public function test_get_prime_factors(): void
    {
        $float = FloatVO::from(12);
        $factors = $float->getPrimeFactors();
        $values = array_map(fn ($f) => $f->getValue(), $factors->toArray());
        $this->assertEquals([2, 2, 3], $values);
    }

    public function test_gcd(): void
    {
        $float = FloatVO::from(12);
        $gcd = $float->gcd(18);
        $this->assertEquals(6, $gcd->getValue());
    }

    public function test_gcd_with_int(): void
    {
        $float = FloatVO::from(12);
        $gcd = $float->gcd(18);
        $this->assertEquals(6, $gcd->getValue());
    }

    public function test_lcm(): void
    {
        $float = FloatVO::from(12);
        $lcm = $float->lcm(18);
        $this->assertEquals(36, $lcm->getValue());
    }

    public function test_lcm_with_zero(): void
    {
        $float = FloatVO::from(0);
        $lcm = $float->lcm(18);
        $this->assertEquals(0, $lcm->getValue());
    }

    // ========== TESTS DE CONVERSION ==========

    public function test_to_int_returns_int(): void
    {
        $float = FloatVO::from(42.9);
        $int = $float->toInt();
        $this->assertEquals(42, $int);
    }

    public function test_to_int_negative(): void
    {
        $float = FloatVO::from(-42.9);
        $int = $float->toInt();
        $this->assertEquals(-42, $int);
    }

    public function test_to_float_returns_same(): void
    {
        $float = FloatVO::from(42.5);
        $this->assertSame($float, $float->toFloat());
    }

    public function test_to_int_ceil(): void
    {
        $float = FloatVO::from(42.1);
        $int = $float->toIntCeil();
        $this->assertEquals(43, $int);
    }

    public function test_to_int_floor(): void
    {
        $float = FloatVO::from(42.9);
        $int = $float->toIntFloor();
        $this->assertEquals(42, $int);
    }

    public function test_to_int_round(): void
    {
        $float = FloatVO::from(42.5);
        $int = $float->toIntRound();
        $this->assertEquals(43, $int);
    }

    // ========== TESTS DES VÉRIFICATIONS ==========

    public function test_is_zero_returns_true_for_zero(): void
    {
        $float = FloatVO::from(0.0);
        $this->assertTrue($float->isZero()->getValue());
    }

    public function test_is_zero_returns_false_for_non_zero(): void
    {
        $float = FloatVO::from(5.5);
        $this->assertFalse($float->isZero()->getValue());
    }

    public function test_is_positive_returns_true_for_positive(): void
    {
        $float = FloatVO::from(5.5);
        $this->assertTrue($float->isPositive()->getValue());
    }

    public function test_is_positive_returns_false_for_negative(): void
    {
        $float = FloatVO::from(-5.5);
        $this->assertFalse($float->isPositive()->getValue());
    }

    public function test_is_negative_returns_true_for_negative(): void
    {
        $float = FloatVO::from(-5.5);
        $this->assertTrue($float->isNegative()->getValue());
    }

    public function test_is_negative_returns_false_for_positive(): void
    {
        $float = FloatVO::from(5.5);
        $this->assertFalse($float->isNegative()->getValue());
    }

    public function test_is_even_returns_true_for_even(): void
    {
        $float = FloatVO::from(4.0);
        $this->assertTrue($float->isEven()->getValue());
    }

    public function test_is_even_returns_false_for_odd(): void
    {
        $float = FloatVO::from(5.0);
        $this->assertFalse($float->isEven()->getValue());
    }

    public function test_is_odd_returns_true_for_odd(): void
    {
        $float = FloatVO::from(5.0);
        $this->assertTrue($float->isOdd()->getValue());
    }

    public function test_is_odd_returns_false_for_even(): void
    {
        $float = FloatVO::from(4.0);
        $this->assertFalse($float->isOdd()->getValue());
    }

    public function test_is_integer_returns_true_for_integer(): void
    {
        $float = FloatVO::from(5.0);
        $this->assertTrue($float->isInteger()->getValue());
    }

    public function test_is_integer_returns_false_for_non_integer(): void
    {
        $float = FloatVO::from(5.5);
        $this->assertFalse($float->isInteger()->getValue());
    }

    public function test_is_finite_returns_true_for_finite_number(): void
    {
        $float = FloatVO::from(5.5);
        $this->assertTrue($float->isFinite()->getValue());
    }

    public function test_is_infinite_returns_false_for_finite_number(): void
    {
        $float = FloatVO::from(5.5);
        $this->assertFalse($float->isInfinite()->getValue());
    }

    public function test_is_nan_returns_false_for_valid_number(): void
    {
        $float = FloatVO::from(5.5);
        $this->assertFalse($float->isNan()->getValue());
    }

    public function test_is_prime_returns_true_for_integer_float(): void
    {
        $float = FloatVO::from(7.0);
        $this->assertTrue($float->isPrime()->getValue());
    }

    public function test_is_prime_returns_false_for_non_integer_float(): void
    {
        $float = FloatVO::from(7.5);
        $this->assertFalse($float->isPrime()->getValue());
    }

    public function test_is_prime_returns_false_for_non_prime_integer_float(): void
    {
        $float = FloatVO::from(8.0);
        $this->assertFalse($float->isPrime()->getValue());
    }

    public function test_is_divisible_by_returns_true_when_divisible(): void
    {
        $float = FloatVO::from(10.0);
        $this->assertTrue($float->isDivisibleBy(2)->getValue());
    }

    public function test_is_divisible_by_returns_false_when_not_divisible(): void
    {
        $float = FloatVO::from(10.0);
        $this->assertFalse($float->isDivisibleBy(3)->getValue());
    }

    // ========== TESTS DE COMPARAISON ==========

    public function test_sign_returns_one_for_positive(): void
    {
        $float = FloatVO::from(10.0);
        $sign = $float->sign();
        $this->assertEquals(1.0, $sign->getValue());
    }

    public function test_sign_returns_minus_one_for_negative(): void
    {
        $float = FloatVO::from(-10.0);
        $sign = $float->sign();
        $this->assertEquals(-1.0, $sign->getValue());
    }

    public function test_sign_returns_zero_for_zero(): void
    {
        $float = FloatVO::from(0.0);
        $sign = $float->sign();
        $this->assertEquals(0.0, $sign->getValue());
    }

    public function test_compare_returns_zero_for_equal(): void
    {
        $float = FloatVO::from(10.0);
        $result = $float->compare(10.0);
        $this->assertEquals(0.0, $result->getValue());
    }

    public function test_compare_returns_one_for_greater(): void
    {
        $float = FloatVO::from(10.0);
        $result = $float->compare(5.0);
        $this->assertEquals(1.0, $result->getValue());
    }

    public function test_compare_returns_minus_one_for_less(): void
    {
        $float = FloatVO::from(5.0);
        $result = $float->compare(10.0);
        $this->assertEquals(-1.0, $result->getValue());
    }

    public function test_equal_returns_true_for_equal_values(): void
    {
        $float = FloatVO::from(10.0);
        $this->assertTrue($float->equal(10.0)->getValue());
    }

    public function test_equal_returns_false_for_different_values(): void
    {
        $float = FloatVO::from(10.0);
        $this->assertFalse($float->equal(5.0)->getValue());
    }

    public function test_greater_than_returns_true_when_greater(): void
    {
        $float = FloatVO::from(10.0);
        $this->assertTrue($float->greaterThan(5.0)->getValue());
    }

    public function test_less_than_returns_true_when_less(): void
    {
        $float = FloatVO::from(5.0);
        $this->assertTrue($float->lessThan(10.0)->getValue());
    }

    public function test_min_returns_smaller_number(): void
    {
        $float = FloatVO::from(10.0);
        $min = $float->min(5.0);
        $this->assertEquals(5.0, $min->getValue());
    }

    public function test_max_returns_larger_number(): void
    {
        $float = FloatVO::from(10.0);
        $max = $float->max(5.0);
        $this->assertEquals(10.0, $max->getValue());
    }

    public function test_clamp_returns_clamped_value(): void
    {
        $float = FloatVO::from(10.0);
        $clamped = $float->clamp(0.0, 5.0);
        $this->assertEquals(5.0, $clamped->getValue());
    }

    public function test_clamp_returns_same_when_within_bounds(): void
    {
        $float = FloatVO::from(3.0);
        $clamped = $float->clamp(0.0, 5.0);
        $this->assertEquals(3.0, $clamped->getValue());
    }

    public function test_clamp_throws_exception_when_min_greater_than_max(): void
    {
        $float = FloatVO::from(3.0);
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Min cannot be greater than max');
        $float->clamp(10.0, 5.0);
    }

    // ========== TESTS DE COMPARAISON AVEC TOLÉRANCE ==========

    public function test_equals_with_tolerance_returns_true(): void
    {
        $float = FloatVO::from(0.1 + 0.2);
        $this->assertTrue($float->equalsWithTolerance(0.3, 1e-10)->getValue());
    }

    public function test_equals_with_tolerance_returns_false(): void
    {
        $float = FloatVO::from(0.1);
        $this->assertFalse($float->equalsWithTolerance(0.2, 1e-10)->getValue());
    }

    // ========== TESTS DE FORMATAGE ==========

    public function test_format_returns_formatted_string(): void
    {
        $float = FloatVO::from(1234567.89);
        $result = $float->format(2, ',', ' ');
        $this->assertEquals('1 234 567,89', $result->getValue());
    }

    public function test_format_without_parameters(): void
    {
        $float = FloatVO::from(1234567.89);
        $result = $float->format();
        $this->assertEquals('1,234,568', $result->getValue());
    }

    public function test_format_with_int_decimals(): void
    {
        $float = FloatVO::from(1234.567);
        $result = $float->format(2);
        $this->assertEquals('1,234.57', $result->getValue());
    }

    public function test_format_with_string_decimals(): void
    {
        $float = FloatVO::from(1234.567);
        $result = $float->format('2');
        $this->assertEquals('1,234.57', $result->getValue());
    }

    // ========== TESTS DE CHAÎNAGE ==========

    public function test_chaining_operations(): void
    {
        $result = FloatVO::from(10.5)
            ->add(5.5)
            ->multiply(2.0)
            ->subtract(10.0)
            ->divide(4.0);

        $this->assertEquals(5.5, $result->getValue());
    }

    public function test_complex_chaining(): void
    {
        $result = FloatVO::from(5)
            ->pow(2)
            ->add(10)
            ->sqrt()
            ->round(2)
            ->abs();

        $this->assertEquals(5.92, round($result->getValue(), 2));
    }

    // ========== TEST DE L'IMMUTABILITÉ ==========

    public function test_immutability(): void
    {
        $original = FloatVO::from(5.5);
        $original->add(2);
        $original->multiply(2);

        $this->assertEquals(5.5, $original->getValue());
    }

    // ========== TEST DU TO STRING ==========

    public function test_to_string_magic_method(): void
    {
        $float = FloatVO::from(123.45);
        $this->assertSame('123.45', (string) $float);
    }
}

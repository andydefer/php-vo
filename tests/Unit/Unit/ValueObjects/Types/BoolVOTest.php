<?php

declare(strict_types=1);

namespace AndyDefer\PhpVo\Tests\Unit\ValueObjects\Types;

use AndyDefer\PhpVo\ValueObjects\Types\BoolVO;
use AndyDefer\PhpVo\ValueObjects\Types\FloatVO;
use AndyDefer\PhpVo\ValueObjects\Types\StringVO;
use PHPUnit\Framework\TestCase;

final class BoolVOTest extends TestCase
{
    // ========== TESTS DE CRÉATION ==========

    public function test_create_bool_vo_true(): void
    {
        $bool = BoolVO::from(true);
        $this->assertTrue($bool->getValue());
    }

    public function test_create_bool_vo_false(): void
    {
        $bool = BoolVO::from(false);
        $this->assertFalse($bool->getValue());
    }

    public function test_create_from_int_true(): void
    {
        $bool = BoolVO::from(1);
        $this->assertTrue($bool->getValue());
    }

    public function test_create_from_int_false(): void
    {
        $bool = BoolVO::from(0);
        $this->assertFalse($bool->getValue());
    }

    public function test_create_from_string_true(): void
    {
        $bool = BoolVO::from('true');
        $this->assertTrue($bool->getValue());
    }

    public function test_create_from_string_false(): void
    {
        $bool = BoolVO::from('false');
        $this->assertFalse($bool->getValue());
    }

    public function test_create_from_string_vo(): void
    {
        $bool = BoolVO::from(StringVO::from('true'));
        $this->assertTrue($bool->getValue());
    }

    public function test_create_from_existing_vo_returns_same_instance(): void
    {
        $original = BoolVO::from(true);
        $duplicate = BoolVO::from($original);
        $this->assertSame($original, $duplicate);
    }

    // ========== TESTS DES OPÉRATIONS LOGIQUES ==========

    public function test_and_returns_true_when_both_true(): void
    {
        $bool1 = BoolVO::from(true);
        $bool2 = BoolVO::from(true);
        $result = $bool1->and($bool2);
        $this->assertTrue($result->getValue());
    }

    public function test_and_returns_false_when_one_false(): void
    {
        $bool1 = BoolVO::from(true);
        $bool2 = BoolVO::from(false);
        $result = $bool1->and($bool2);
        $this->assertFalse($result->getValue());
    }

    public function test_and_with_scalar(): void
    {
        $bool = BoolVO::from(true);
        $result = $bool->and(1);
        $this->assertTrue($result->getValue());
    }

    public function test_or_returns_true_when_one_true(): void
    {
        $bool1 = BoolVO::from(true);
        $bool2 = BoolVO::from(false);
        $result = $bool1->or($bool2);
        $this->assertTrue($result->getValue());
    }

    public function test_or_returns_false_when_both_false(): void
    {
        $bool1 = BoolVO::from(false);
        $bool2 = BoolVO::from(false);
        $result = $bool1->or($bool2);
        $this->assertFalse($result->getValue());
    }

    public function test_or_with_scalar(): void
    {
        $bool = BoolVO::from(false);
        $result = $bool->or(1);
        $this->assertTrue($result->getValue());
    }

    public function test_xor_returns_true_when_different(): void
    {
        $bool1 = BoolVO::from(true);
        $bool2 = BoolVO::from(false);
        $result = $bool1->xor($bool2);
        $this->assertTrue($result->getValue());
    }

    public function test_xor_returns_false_when_same(): void
    {
        $bool1 = BoolVO::from(true);
        $bool2 = BoolVO::from(true);
        $result = $bool1->xor($bool2);
        $this->assertFalse($result->getValue());
    }

    public function test_not_returns_false_when_true(): void
    {
        $bool = BoolVO::from(true);
        $result = $bool->not();
        $this->assertFalse($result->getValue());
    }

    public function test_not_returns_true_when_false(): void
    {
        $bool = BoolVO::from(false);
        $result = $bool->not();
        $this->assertTrue($result->getValue());
    }

    public function test_nand_returns_false_when_both_true(): void
    {
        $bool1 = BoolVO::from(true);
        $bool2 = BoolVO::from(true);
        $result = $bool1->nand($bool2);
        $this->assertFalse($result->getValue());
    }

    public function test_nand_returns_true_when_one_false(): void
    {
        $bool1 = BoolVO::from(true);
        $bool2 = BoolVO::from(false);
        $result = $bool1->nand($bool2);
        $this->assertTrue($result->getValue());
    }

    public function test_nor_returns_true_when_both_false(): void
    {
        $bool1 = BoolVO::from(false);
        $bool2 = BoolVO::from(false);
        $result = $bool1->nor($bool2);
        $this->assertTrue($result->getValue());
    }

    public function test_nor_returns_false_when_one_true(): void
    {
        $bool1 = BoolVO::from(true);
        $bool2 = BoolVO::from(false);
        $result = $bool1->nor($bool2);
        $this->assertFalse($result->getValue());
    }

    public function test_xnor_returns_true_when_same(): void
    {
        $bool1 = BoolVO::from(true);
        $bool2 = BoolVO::from(true);
        $result = $bool1->xnor($bool2);
        $this->assertTrue($result->getValue());
    }

    public function test_xnor_returns_false_when_different(): void
    {
        $bool1 = BoolVO::from(true);
        $bool2 = BoolVO::from(false);
        $result = $bool1->xnor($bool2);
        $this->assertFalse($result->getValue());
    }

    // ========== TESTS DE COMPARAISON ==========

    public function test_equal_returns_true_when_same(): void
    {
        $bool1 = BoolVO::from(true);
        $bool2 = BoolVO::from(true);
        $result = $bool1->equal($bool2);
        $this->assertTrue($result->getValue());
    }

    public function test_equal_returns_false_when_different(): void
    {
        $bool1 = BoolVO::from(true);
        $bool2 = BoolVO::from(false);
        $result = $bool1->equal($bool2);
        $this->assertFalse($result->getValue());
    }

    public function test_not_equals_returns_false_when_same(): void
    {
        $bool1 = BoolVO::from(true);
        $bool2 = BoolVO::from(true);
        $result = $bool1->notEquals($bool2);
        $this->assertFalse($result->getValue());
    }

    public function test_not_equals_returns_true_when_different(): void
    {
        $bool1 = BoolVO::from(true);
        $bool2 = BoolVO::from(false);
        $result = $bool1->notEquals($bool2);
        $this->assertTrue($result->getValue());
    }

    // ========== TESTS DES VÉRIFICATIONS ==========

    public function test_is_true_returns_true_when_true(): void
    {
        $bool = BoolVO::from(true);
        $result = $bool->isTrue();
        $this->assertTrue($result->getValue());
    }

    public function test_is_true_returns_false_when_false(): void
    {
        $bool = BoolVO::from(false);
        $result = $bool->isTrue();
        $this->assertFalse($result->getValue());
    }

    public function test_is_false_returns_true_when_false(): void
    {
        $bool = BoolVO::from(false);
        $result = $bool->isFalse();
        $this->assertTrue($result->getValue());
    }

    public function test_is_false_returns_false_when_true(): void
    {
        $bool = BoolVO::from(true);
        $result = $bool->isFalse();
        $this->assertFalse($result->getValue());
    }

    // ========== TESTS DE CONVERSION ==========

    public function test_to_int_returns_1_for_true(): void
    {
        $bool = BoolVO::from(true);
        $int = $bool->toInt();
        $this->assertEquals(1, $int);
    }

    public function test_to_int_returns_0_for_false(): void
    {
        $bool = BoolVO::from(false);
        $int = $bool->toInt();
        $this->assertEquals(0, $int);
    }

    public function test_to_float_returns_1_0_for_true(): void
    {
        $bool = BoolVO::from(true);
        $float = $bool->toFloat();
        $this->assertInstanceOf(FloatVO::class, $float);
        $this->assertEquals(1.0, $float->getValue());
    }

    public function test_to_float_returns_0_0_for_false(): void
    {
        $bool = BoolVO::from(false);
        $float = $bool->toFloat();
        $this->assertInstanceOf(FloatVO::class, $float);
        $this->assertEquals(0.0, $float->getValue());
    }

    public function test_to_string_returns_true_string(): void
    {
        $bool = BoolVO::from(true);
        $string = $bool->toString();
        $this->assertInstanceOf(StringVO::class, $string);
        $this->assertEquals('true', $string->getValue());
    }

    public function test_to_string_returns_false_string(): void
    {
        $bool = BoolVO::from(false);
        $string = $bool->toString();
        $this->assertInstanceOf(StringVO::class, $string);
        $this->assertEquals('false', $string->getValue());
    }

    // ========== TESTS DES OPÉRATIONS CONDITIONNELLES ==========

    public function test_if_true_executes_callback(): void
    {
        $executed = false;
        $bool = BoolVO::from(true);
        $bool->ifTrue(function () use (&$executed) {
            $executed = true;
        });
        $this->assertTrue($executed);
    }

    public function test_if_true_does_not_execute_callback_when_false(): void
    {
        $executed = false;
        $bool = BoolVO::from(false);
        $bool->ifTrue(function () use (&$executed) {
            $executed = true;
        });
        $this->assertFalse($executed);
    }

    public function test_if_false_executes_callback(): void
    {
        $executed = false;
        $bool = BoolVO::from(false);
        $bool->ifFalse(function () use (&$executed) {
            $executed = true;
        });
        $this->assertTrue($executed);
    }

    public function test_if_false_does_not_execute_callback_when_true(): void
    {
        $executed = false;
        $bool = BoolVO::from(true);
        $bool->ifFalse(function () use (&$executed) {
            $executed = true;
        });
        $this->assertFalse($executed);
    }

    public function test_if_else_executes_true_callback(): void
    {
        $bool = BoolVO::from(true);
        $result = $bool->ifElse(
            fn () => 'true',
            fn () => 'false'
        );
        $this->assertEquals('true', $result);
    }

    public function test_if_else_executes_false_callback(): void
    {
        $bool = BoolVO::from(false);
        $result = $bool->ifElse(
            fn () => 'true',
            fn () => 'false'
        );
        $this->assertEquals('false', $result);
    }

    // ========== TESTS DES OPÉRATIONS AVEC D'AUTRES TYPES ==========

    public function test_and_with_evaluation_returns_true_when_both_true(): void
    {
        $bool = BoolVO::from(true);
        $result = $bool->andWith(fn () => true);
        $this->assertTrue($result->getValue());
    }

    public function test_and_with_evaluation_returns_false_when_one_false(): void
    {
        $bool = BoolVO::from(true);
        $result = $bool->andWith(fn () => false);
        $this->assertFalse($result->getValue());
    }

    public function test_or_with_evaluation_returns_true_when_one_true(): void
    {
        $bool = BoolVO::from(false);
        $result = $bool->orWith(fn () => true);
        $this->assertTrue($result->getValue());
    }

    public function test_or_with_evaluation_returns_false_when_both_false(): void
    {
        $bool = BoolVO::from(false);
        $result = $bool->orWith(fn () => false);
        $this->assertFalse($result->getValue());
    }

    // ========== TESTS DE CHAÎNAGE ==========

    public function test_chaining_operations(): void
    {
        $result = BoolVO::from(true)
            ->and(true)
            ->or(false)
            ->and(false)
            ->not()
            ->xnor(true);

        $this->assertTrue($result->getValue());
    }

    public function test_complex_chaining(): void
    {
        $result = BoolVO::from(true)
            ->and(BoolVO::from(true))
            ->or(BoolVO::from(false))
            ->not()
            ->and(BoolVO::from(true));

        // Le résultat réel est false (évaluation de gauche à droite)
        $this->assertFalse($result->getValue());
    }

    // ========== TEST DE L'IMMUTABILITÉ ==========

    public function test_immutability(): void
    {
        $original = BoolVO::from(true);
        $original->and(false);
        $original->or(false);
        $original->not();

        $this->assertTrue($original->getValue());
    }

    // ========== TEST DU TO STRING ==========

    public function test_to_string_magic_method(): void
    {
        $bool = BoolVO::from(true);
        $this->assertSame('true', (string) $bool);
    }

    public function test_to_string_magic_method_false(): void
    {
        $bool = BoolVO::from(false);
        $this->assertSame('false', (string) $bool);
    }
}

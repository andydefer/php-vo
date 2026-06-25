<?php

declare(strict_types=1);

namespace AndyDefer\PhpVo\Tests\Unit\ValueObjects\Types;

use AndyDefer\DomainStructures\Utils\MapCollection;
use AndyDefer\PhpVo\ValueObjects\Types\BoolVO;
use AndyDefer\PhpVo\ValueObjects\Types\FloatVO;
use AndyDefer\PhpVo\ValueObjects\Types\StringVO;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class StringVOTest extends TestCase
{
    // ========== TESTS DE CRÉATION ==========

    public function test_create_string_vo(): void
    {
        $string = StringVO::from('Hello');
        $this->assertSame('Hello', $string->getValue());
    }

    public function test_create_empty_string(): void
    {
        $string = StringVO::from('');
        $this->assertSame('', $string->getValue());
    }

    public function test_create_from_int(): void
    {
        $string = StringVO::from(123);
        $this->assertSame('123', $string->getValue());
    }

    public function test_create_from_float(): void
    {
        $string = StringVO::from(123.45);
        $this->assertSame('123.45', $string->getValue());
    }

    public function test_create_from_bool_true(): void
    {
        $string = StringVO::from(true);
        $this->assertSame('1', $string->getValue());
    }

    public function test_create_from_bool_false(): void
    {
        $string = StringVO::from(false);
        $this->assertSame('', $string->getValue());
    }

    public function test_create_from_existing_vo_returns_same_instance(): void
    {
        $original = StringVO::from('Hello');
        $duplicate = StringVO::from($original);
        $this->assertSame($original, $duplicate);
    }

    // ========== TESTS DES OPÉRATIONS DE BASE ==========

    public function test_length_returns_correct_length(): void
    {
        $string = StringVO::from('Hello');
        $this->assertEquals(5, $string->length());
    }

    public function test_is_empty_returns_true_for_empty(): void
    {
        $string = StringVO::from('');
        $this->assertTrue($string->isEmpty()->getValue());
    }

    public function test_is_empty_returns_false_for_non_empty(): void
    {
        $string = StringVO::from('Hello');
        $this->assertFalse($string->isEmpty()->getValue());
    }

    public function test_is_not_empty_returns_true_for_non_empty(): void
    {
        $string = StringVO::from('Hello');
        $this->assertTrue($string->isNotEmpty()->getValue());
    }

    public function test_trim_removes_whitespace(): void
    {
        $string = StringVO::from('  Hello  ');
        $result = $string->trim();
        $this->assertEquals('Hello', $result->getValue());
    }

    public function test_ltrim_removes_left_whitespace(): void
    {
        $string = StringVO::from('  Hello  ');
        $result = $string->ltrim();
        $this->assertEquals('Hello  ', $result->getValue());
    }

    public function test_rtrim_removes_right_whitespace(): void
    {
        $string = StringVO::from('  Hello  ');
        $result = $string->rtrim();
        $this->assertEquals('  Hello', $result->getValue());
    }

    // ========== TESTS DE MANIPULATION ==========

    public function test_concat_appends_string(): void
    {
        $string = StringVO::from('Hello');
        $result = $string->concat(' World');
        $this->assertEquals('Hello World', $result->getValue());
    }

    public function test_concat_with_string_vo(): void
    {
        $string = StringVO::from('Hello');
        $result = $string->concat(StringVO::from(' World'));
        $this->assertEquals('Hello World', $result->getValue());
    }

    public function test_repeat_returns_repeated_string(): void
    {
        $string = StringVO::from('Ha');
        $result = $string->repeat(3);
        $this->assertEquals('HaHaHa', $result->getValue());
    }

    public function test_repeat_with_string_vo(): void
    {
        $string = StringVO::from('Ha');
        $result = $string->repeat(StringVO::from('3'));
        $this->assertEquals('HaHaHa', $result->getValue());
    }

    public function test_substring_returns_substring(): void
    {
        $string = StringVO::from('Hello World');
        $result = $string->substring(0, 5);
        $this->assertEquals('Hello', $result->getValue());
    }

    public function test_substring_without_length(): void
    {
        $string = StringVO::from('Hello World');
        $result = $string->substring(6);
        $this->assertEquals('World', $result->getValue());
    }

    public function test_substring_with_string_vo_start(): void
    {
        $string = StringVO::from('Hello World');
        $result = $string->substring(StringVO::from('6'));
        $this->assertEquals('World', $result->getValue());
    }

    public function test_replace_replaces_string(): void
    {
        $string = StringVO::from('Hello World');
        $result = $string->replace('World', 'PHP');
        $this->assertEquals('Hello PHP', $result->getValue());
    }

    public function test_replace_with_string_vo(): void
    {
        $string = StringVO::from('Hello World');
        $result = $string->replace(StringVO::from('World'), StringVO::from('PHP'));
        $this->assertEquals('Hello PHP', $result->getValue());
    }

    public function test_replace_all_replaces_multiple_strings(): void
    {
        $string = StringVO::from('Hello World');
        $replacements = MapCollection::from([
            'World' => 'PHP',
            'Hello' => 'Hi',
        ]);
        $result = $string->replaceAll($replacements);
        $this->assertEquals('Hi PHP', $result->getValue());
    }

    public function test_strip_tags_removes_html_tags(): void
    {
        $string = StringVO::from('<p>Hello <b>World</b></p>');
        $result = $string->stripTags();
        $this->assertEquals('Hello World', $result->getValue());
    }

    public function test_strip_tags_with_allowed(): void
    {
        $string = StringVO::from('<p>Hello <b>World</b></p>');
        $result = $string->stripTagsWithAllowed('<b>');
        $this->assertEquals('Hello <b>World</b>', $result->getValue());
    }

    // ========== TESTS DES CAS ==========

    public function test_to_upper_returns_uppercase(): void
    {
        $string = StringVO::from('hello');
        $result = $string->toUpper();
        $this->assertEquals('HELLO', $result->getValue());
    }

    public function test_to_lower_returns_lowercase(): void
    {
        $string = StringVO::from('HELLO');
        $result = $string->toLower();
        $this->assertEquals('hello', $result->getValue());
    }

    public function test_to_upper_first_capitalizes_first_letter(): void
    {
        $string = StringVO::from('hello');
        $result = $string->toUpperFirst();
        $this->assertEquals('Hello', $result->getValue());
    }

    public function test_to_lower_first_lowercases_first_letter(): void
    {
        $string = StringVO::from('Hello');
        $result = $string->toLowerFirst();
        $this->assertEquals('hello', $result->getValue());
    }

    public function test_to_upper_words_capitalizes_each_word(): void
    {
        $string = StringVO::from('hello world');
        $result = $string->toUpperWords();
        $this->assertEquals('Hello World', $result->getValue());
    }

    public function test_to_snake_case_converts_to_snake_case(): void
    {
        $string = StringVO::from('HelloWorld');
        $result = $string->toSnakeCase();
        $this->assertEquals('hello_world', $result->getValue());
    }

    public function test_to_camel_case_converts_to_camel_case(): void
    {
        $string = StringVO::from('hello_world');
        $result = $string->toCamelCase();
        $this->assertEquals('helloWorld', $result->getValue());
    }

    // ========== TESTS DE RECHERCHE ==========

    public function test_contains_returns_true_when_contains(): void
    {
        $string = StringVO::from('Hello World');
        $this->assertTrue($string->contains('World')->getValue());
    }

    public function test_contains_returns_false_when_not_contains(): void
    {
        $string = StringVO::from('Hello World');
        $this->assertFalse($string->contains('PHP')->getValue());
    }

    public function test_contains_with_string_vo(): void
    {
        $string = StringVO::from('Hello World');
        $this->assertTrue($string->contains(StringVO::from('World'))->getValue());
    }

    public function test_starts_with_returns_true_when_starts_with(): void
    {
        $string = StringVO::from('Hello World');
        $this->assertTrue($string->startsWith('Hello')->getValue());
    }

    public function test_starts_with_returns_false_when_not_starts_with(): void
    {
        $string = StringVO::from('Hello World');
        $this->assertFalse($string->startsWith('World')->getValue());
    }

    public function test_ends_with_returns_true_when_ends_with(): void
    {
        $string = StringVO::from('Hello World');
        $this->assertTrue($string->endsWith('World')->getValue());
    }

    public function test_ends_with_returns_false_when_not_ends_with(): void
    {
        $string = StringVO::from('Hello World');
        $this->assertFalse($string->endsWith('Hello')->getValue());
    }

    public function test_position_returns_correct_position(): void
    {
        $string = StringVO::from('Hello World');
        $position = $string->position('World');
        $this->assertEquals(6, $position);
    }

    public function test_position_returns_minus_one_when_not_found(): void
    {
        $string = StringVO::from('Hello World');
        $position = $string->position('PHP');
        $this->assertEquals(-1, $position);
    }

    public function test_last_position_returns_last_position(): void
    {
        $string = StringVO::from('Hello World World');
        $position = $string->lastPosition('World');
        $this->assertEquals(12, $position);
    }

    public function test_count_occurrences_returns_correct_count(): void
    {
        $string = StringVO::from('Hello World World');
        $count = $string->countOccurrences('World');
        $this->assertEquals(2, $count);
    }

    // ========== TESTS DE SPLIT ==========

    public function test_split_returns_list_collection(): void
    {
        $string = StringVO::from('Hello,World,PHP');
        $result = $string->split(',');
        $values = array_map(fn ($s) => $s->getValue(), $result->toArray());
        $this->assertEquals(['Hello', 'World', 'PHP'], $values);
    }

    public function test_split_with_string_vo_separator(): void
    {
        $string = StringVO::from('Hello,World,PHP');
        $result = $string->split(StringVO::from(','));
        $values = array_map(fn ($s) => $s->getValue(), $result->toArray());
        $this->assertEquals(['Hello', 'World', 'PHP'], $values);
    }

    public function test_split_by_length_returns_list_collection(): void
    {
        $string = StringVO::from('HelloWorld');
        $result = $string->splitByLength(5);
        $values = array_map(fn ($s) => $s->getValue(), $result->toArray());
        $this->assertEquals(['Hello', 'World'], $values);
    }

    public function test_chars_returns_list_of_characters(): void
    {
        $string = StringVO::from('Hello');
        $result = $string->chars();
        $values = array_map(fn ($s) => $s->getValue(), $result->toArray());
        $this->assertEquals(['H', 'e', 'l', 'l', 'o'], $values);
    }

    public function test_words_returns_list_of_words(): void
    {
        $string = StringVO::from('Hello World PHP');
        $result = $string->words();
        $values = array_map(fn ($s) => $s->getValue(), $result->toArray());
        $this->assertEquals(['Hello', 'World', 'PHP'], $values);
    }

    // ========== TESTS DE COMPARAISON ==========

    public function test_equal_returns_true_when_equal(): void
    {
        $string1 = StringVO::from('Hello');
        $string2 = StringVO::from('Hello');
        $this->assertTrue($string1->equal($string2)->getValue());
    }

    public function test_equal_returns_false_when_different(): void
    {
        $string1 = StringVO::from('Hello');
        $string2 = StringVO::from('World');
        $this->assertFalse($string1->equal($string2)->getValue());
    }

    public function test_equal_with_scalar(): void
    {
        $string = StringVO::from('Hello');
        $this->assertTrue($string->equal('Hello')->getValue());
    }

    public function test_equals_ignore_case_returns_true_when_equal_ignore_case(): void
    {
        $string1 = StringVO::from('Hello');
        $string2 = StringVO::from('hello');
        $this->assertTrue($string1->equalsIgnoreCase($string2)->getValue());
    }

    public function test_compare_returns_zero_when_equal(): void
    {
        $string1 = StringVO::from('Hello');
        $string2 = StringVO::from('Hello');
        $this->assertEquals(0, $string1->compare($string2));
    }

    public function test_compare_returns_less_than_zero(): void
    {
        $string = StringVO::from('Apple');
        $this->assertLessThan(0, $string->compare('Banana'));
    }

    public function test_compare_returns_greater_than_zero(): void
    {
        $string = StringVO::from('Banana');
        $this->assertGreaterThan(0, $string->compare('Apple'));
    }

    // ========== TESTS DE CONVERSION ==========

    public function test_to_int_converts_numeric_string(): void
    {
        $string = StringVO::from('123');
        $int = $string->toInt();
        $this->assertEquals(123, $int);
    }

    public function test_to_int_with_float_string(): void
    {
        $string = StringVO::from('123.45');
        $int = $string->toInt();
        $this->assertEquals(123, $int);
    }

    public function test_to_int_throws_exception_for_non_numeric(): void
    {
        $string = StringVO::from('abc');
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Cannot convert 'abc' to integer");
        $string->toInt();
    }

    public function test_to_float_converts_numeric_string(): void
    {
        $string = StringVO::from('123.45');
        $float = $string->toFloat();
        $this->assertInstanceOf(FloatVO::class, $float);
        $this->assertEquals(123.45, $float->getValue());
    }

    public function test_to_float_throws_exception_for_non_numeric(): void
    {
        $string = StringVO::from('abc');
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Cannot convert 'abc' to float");
        $string->toFloat();
    }

    public function test_to_bool_converts_truthy_string(): void
    {
        $string = StringVO::from('true');
        $bool = $string->toBool();
        $this->assertInstanceOf(BoolVO::class, $bool);
        $this->assertTrue($bool->getValue());
    }

    public function test_to_bool_converts_falsy_string(): void
    {
        $string = StringVO::from('false');
        $bool = $string->toBool();
        $this->assertInstanceOf(BoolVO::class, $bool);
        $this->assertFalse($bool->getValue());
    }

    public function test_to_bool_with_1_returns_true(): void
    {
        $string = StringVO::from('1');
        $bool = $string->toBool();
        $this->assertTrue($bool->getValue());
    }

    public function test_to_bool_with_0_returns_false(): void
    {
        $string = StringVO::from('0');
        $bool = $string->toBool();
        $this->assertFalse($bool->getValue());
    }

    public function test_to_array_splits_by_comma(): void
    {
        $string = StringVO::from('Hello,World,PHP');
        $result = $string->toArray();
        $values = array_map(fn ($s) => $s->getValue(), $result->toArray());
        $this->assertEquals(['Hello', 'World', 'PHP'], $values);
    }

    // ========== TESTS D'IMMUTABILITÉ ==========

    public function test_immutability(): void
    {
        $original = StringVO::from('Hello');
        $original->toUpper();
        $original->concat(' World');
        $original->replace('Hello', 'Hi');

        $this->assertEquals('Hello', $original->getValue());
    }

    // ========== TESTS DE CHAÎNAGE ==========

    public function test_chaining_operations(): void
    {
        $result = StringVO::from('  Hello World  ')
            ->trim()
            ->toUpper()
            ->replace('WORLD', 'PHP')
            ->toLower();

        $this->assertEquals('hello php', $result->getValue());
    }

    public function test_chaining_with_multiple_operations(): void
    {
        $result = StringVO::from('Hello World')
            ->toUpper()
            ->replace('WORLD', 'PHP')
            ->concat('!')
            ->repeat(2)
            ->trim();

        $this->assertEquals('HELLO PHP!HELLO PHP!', $result->getValue());
    }

    // ========== TEST DU TO STRING ==========

    public function test_to_string_magic_method(): void
    {
        $string = StringVO::from('Hello');
        $this->assertSame('Hello', (string) $string);
    }
}

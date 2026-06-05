<?php

declare(strict_types=1);

namespace AndyDefer\PhpVo\Tests\Unit\ValueObjects;

use AndyDefer\PhpVo\ValueObjects\SlugVO;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class SlugVOTest extends TestCase
{
    // ==================== CREATE FROM ALREADY VALID SLUG ====================

    public function test_create_valid_slug(): void
    {
        $slug = SlugVO::from('my-article-title-2024');

        $this->assertSame('my-article-title-2024', $slug->getValue());
    }

    public function test_create_slug_with_numbers(): void
    {
        $slug = SlugVO::from('article-123-456');

        $this->assertSame('article-123-456', $slug->getValue());
    }

    public function test_create_slug_single_word(): void
    {
        $slug = SlugVO::from('home');

        $this->assertSame('home', $slug->getValue());
    }

    // ==================== CREATE FROM UNFORMATTED TEXT ====================

    public function test_create_from_sentence_with_spaces(): void
    {
        $slug = SlugVO::from('My Article Title');

        $this->assertSame('my-article-title', $slug->getValue());
    }

    public function test_create_from_sentence_with_accents(): void
    {
        $slug = SlugVO::from('Mon article à tester');

        $this->assertSame('mon-article-a-tester', $slug->getValue());
    }

    public function test_create_with_french_accents(): void
    {
        $slug = SlugVO::from('Café, thé, gâteau');

        $this->assertSame('cafe-the-gateau', $slug->getValue());
    }

    public function test_create_with_apostrophe(): void
    {
        $slug = SlugVO::from("Mon article d'import");

        $this->assertSame('mon-article-d-import', $slug->getValue());
    }

    public function test_create_with_special_characters(): void
    {
        $slug = SlugVO::from('Hello @ World! $100');

        $this->assertSame('hello-at-world-100', $slug->getValue());
    }

    public function test_create_with_ampersand(): void
    {
        $slug = SlugVO::from('Rock & Roll');

        $this->assertSame('rock-and-roll', $slug->getValue());
    }

    public function test_create_with_umlaut(): void
    {
        $slug = SlugVO::from('Müller Straße');

        $this->assertSame('muller-strasse', $slug->getValue());
    }

    public function test_create_with_currency_symbols(): void
    {
        $slug = SlugVO::from('Prix: 100€');

        $this->assertSame('prix-100euro', $slug->getValue());
    }

    public function test_create_with_mixed_case(): void
    {
        $slug = SlugVO::from('My MiXeD CaSe Title');

        $this->assertSame('my-mixed-case-title', $slug->getValue());
    }

    public function test_create_with_numbers_only(): void
    {
        $slug = SlugVO::from('12345');

        $this->assertSame('12345', $slug->getValue());
    }

    public function test_create_with_numbers_and_letters(): void
    {
        $slug = SlugVO::from('Article 123 Version 2.0');

        // Le point dans '2.0' est supprimé → '20'
        $this->assertSame('article-123-version-20', $slug->getValue());
    }

    public function test_create_with_multiple_spaces(): void
    {
        $slug = SlugVO::from('This   has   many   spaces');

        $this->assertSame('this-has-many-spaces', $slug->getValue());
    }

    public function test_create_with_trailing_and_leading_spaces(): void
    {
        $slug = SlugVO::from('  trimmed slug  ');

        $this->assertSame('trimmed-slug', $slug->getValue());
    }

    // ==================== TRANSFORMATION METHODS TESTS ====================

    public function test_to_readable_string(): void
    {
        $slug = SlugVO::from('my-awesome-article-2024');

        $this->assertSame('My Awesome Article 2024', $slug->toReadableString());
    }

    public function test_to_readable_string_from_unformatted(): void
    {
        $slug = SlugVO::from('Mon article à tester');

        $this->assertSame('Mon Article A Tester', $slug->toReadableString());
    }

    public function test_to_kebab_case(): void
    {
        $slug = SlugVO::from('my-article-title');

        $this->assertSame('my-article-title', $slug->toKebabCase());
    }

    public function test_to_snake_case(): void
    {
        $slug = SlugVO::from('my-article-title');

        $this->assertSame('my_article_title', $slug->toSnakeCase());
    }

    public function test_to_camel_case(): void
    {
        $slug = SlugVO::from('my-article-title');

        $this->assertSame('myArticleTitle', $slug->toCamelCase());
    }

    public function test_to_camel_case_single_word(): void
    {
        $slug = SlugVO::from('home');

        $this->assertSame('home', $slug->toCamelCase());
    }

    public function test_to_pascal_case(): void
    {
        $slug = SlugVO::from('my-article-title');

        $this->assertSame('MyArticleTitle', $slug->toPascalCase());
    }

    public function test_to_pascal_case_single_word(): void
    {
        $slug = SlugVO::from('home');

        $this->assertSame('Home', $slug->toPascalCase());
    }

    public function test_to_camel_case_from_unformatted(): void
    {
        $slug = SlugVO::from('Mon article à tester');

        $this->assertSame('monArticleATester', $slug->toCamelCase());
    }

    public function test_to_pascal_case_from_unformatted(): void
    {
        $slug = SlugVO::from('Mon article à tester');

        $this->assertSame('MonArticleATester', $slug->toPascalCase());
    }

    // ==================== EXISTING INSTANCE TESTS ====================

    public function test_create_from_existing_slug_returns_same_instance(): void
    {
        $original = SlugVO::from('my-article');
        $duplicate = SlugVO::from($original);

        $this->assertSame($original, $duplicate);
    }

    public function test_create_from_existing_slug_preserves_normalization(): void
    {
        $original = SlugVO::from('my-article');
        $duplicate = SlugVO::from($original);

        $this->assertSame($original->getValue(), $duplicate->getValue());
    }

    // ==================== EQUALITY TESTS ====================

    public function test_equals_returns_true_for_same_slug(): void
    {
        $slug1 = SlugVO::from('my-article');
        $slug2 = SlugVO::from('my-article');

        $this->assertTrue($slug1->equals($slug2));
    }

    public function test_equals_returns_true_for_equivalent_slugs(): void
    {
        $slug1 = SlugVO::from('my-article');
        $slug2 = SlugVO::from('My Article');

        $this->assertTrue($slug1->equals($slug2));
    }

    public function test_equals_returns_false_for_different_slug(): void
    {
        $slug1 = SlugVO::from('my-article');
        $slug2 = SlugVO::from('different-article');

        $this->assertFalse($slug1->equals($slug2));
    }

    // ==================== STRING REPRESENTATION TESTS ====================

    public function test_to_string_magic_method(): void
    {
        $slug = SlugVO::from('my-article-title');

        $this->assertSame('my-article-title', (string)$slug);
    }

    public function test_to_string_magic_method_from_unformatted(): void
    {
        $slug = SlugVO::from('My Article Title');

        $this->assertSame('my-article-title', (string)$slug);
    }

    // ==================== EDGE CASES TESTS ====================

    public function test_handles_empty_string_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unable to create valid slug from: ""');

        SlugVO::from('');
    }



    public function test_handles_mixed_special_characters(): void
    {
        $slug = SlugVO::from('Hello @ World!');

        $this->assertSame('hello-at-world', $slug->getValue());
    }

    public function test_handles_at_symbol_only(): void
    {
        $slug = SlugVO::from('user@example');

        $this->assertSame('useratexample', $slug->getValue());
    }

    public function test_handles_plus_symbol(): void
    {
        $slug = SlugVO::from('2+2=4');

        $this->assertSame('2plus2equals4', $slug->getValue());
    }

    public function test_normalization_removes_consecutive_hyphens(): void
    {
        $slug = SlugVO::from('multiple---hyphens---test');

        $this->assertSame('multiple-hyphens-test', $slug->getValue());
    }

    public function test_handles_cyrillic_characters_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unable to create valid slug from: "Привет мир"');

        SlugVO::from('Привет мир');
    }

    public function test_handles_null_input_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot convert value to string for parameter $value');

        SlugVO::from(null);
    }
}

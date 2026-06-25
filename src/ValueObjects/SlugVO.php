<?php

declare(strict_types=1);

namespace AndyDefer\PhpVo\ValueObjects;

use AndyDefer\DomainStructures\Abstracts\AbstractValueObject;
use InvalidArgumentException;

/**
 * Value Object representing a URL-friendly slug.
 *
 * A slug is a human-readable, URL-safe string typically used in URLs.
 * Format: lowercase alphanumeric characters separated by single hyphens.
 *
 * Supports creation from:
 * - Already formatted slug: SlugVO::from('my-article-title')
 * - Unformatted text: SlugVO::from('Mon Article d\'import') -> 'mon-article-d-import'
 *
 * @example
 * $slug = SlugVO::from('my-article-title-2024');
 * echo $slug->getValue();        // 'my-article-title-2024'
 *
 * $slug = SlugVO::from('Mon Article!');
 * echo $slug->getValue();        // 'mon-article'
 *
 * $slug = SlugVO::from('Hello @ World! $100');
 * echo $slug->getValue();        // 'hello-at-world-100'
 */
final class SlugVO extends AbstractValueObject
{
    private const SLUG_REGEX = '/^[a-z0-9]+(?:-[a-z0-9]+)*$/';

    public readonly string $value;

    public function __construct(string $value)
    {
        $normalized = $this->normalize($value);

        if ($normalized === '' || ! preg_match(self::SLUG_REGEX, $normalized)) {
            throw new InvalidArgumentException(sprintf(
                'Unable to create valid slug from: "%s"',
                $value
            ));
        }

        $this->value = $normalized;
    }

    /**
     * Normalize a string to a valid slug format.
     *
     * @param  string  $string  The string to normalize
     * @return string The normalized slug
     */
    private function normalize(string $string): string
    {
        // Convertir en minuscules
        $slug = mb_strtolower($string, 'UTF-8');

        // Remplacer les caractères accentués par leur équivalent non accentué
        $slug = $this->removeAccents($slug);

        // Remplacer les caractères spéciaux par leur équivalent textuel
        $slug = $this->replaceSpecialChars($slug);

        // Remplacer tout caractère non alphanumérique par un tiret
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);

        // Supprimer les tirets au début et à la fin
        $slug = trim($slug, '-');

        return $slug;
    }

    /**
     * Remove accents from a string.
     *
     * @param  string  $string  The string to process
     * @return string The string without accents
     */
    private function removeAccents(string $string): string
    {
        $accents = [
            '/[áàâãä]/u' => 'a',
            '/[éèêë]/u' => 'e',
            '/[íìîï]/u' => 'i',
            '/[óòôõö]/u' => 'o',
            '/[úùûü]/u' => 'u',
            '/[ýÿ]/u' => 'y',
            '/[ç]/u' => 'c',
            '/[ñ]/u' => 'n',
            '/[æ]/u' => 'ae',
            '/[œ]/u' => 'oe',
            '/[ß]/u' => 'ss',
        ];

        foreach ($accents as $pattern => $replacement) {
            $string = preg_replace($pattern, $replacement, $string);
        }

        return $string;
    }

    /**
     * Replace special characters with their text equivalents.
     *
     * @param  string  $string  The string to process
     * @return string The string with special characters replaced
     */
    private function replaceSpecialChars(string $string): string
    {
        $replacements = [
            '/&/' => 'and',
            '/@/' => 'at',
            '/€/' => 'euro',
            '/\+/' => 'plus',
            '/=/' => 'equals',
            '/\$/' => '',  // supprimé
            '/%/' => '',   // supprimé
            '/\*/' => '',  // supprimé
            '/#/' => '',   // supprimé
            '/\^/' => '',  // supprimé
            '/\?/' => '',  // supprimé
            '/!/' => '',   // supprimé
            '/\./' => '',  // supprimé
            '/:/' => '',   // supprimé
            '/;/' => '',   // supprimé
            '/,/' => '',   // supprimé
        ];

        foreach ($replacements as $pattern => $replacement) {
            $string = preg_replace($pattern, $replacement, $string);
        }

        return $string;
    }

    /**
     * Get the raw slug value.
     *
     * @return string The slug
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Convert slug to a human-readable string.
     * Example: 'my-article-title-2024' → 'My Article Title 2024'
     *
     * @return string Human-readable version
     */
    public function toReadableString(): string
    {
        return ucwords(str_replace('-', ' ', $this->value));
    }

    /**
     * Convert slug to kebab-case (same as original).
     *
     * @return string Kebab-case version
     */
    public function toKebabCase(): string
    {
        return $this->value;
    }

    /**
     * Convert slug to snake_case.
     * Example: 'my-article-title-2024' → 'my_article_title_2024'
     *
     * @return string Snake_case version
     */
    public function toSnakeCase(): string
    {
        return str_replace('-', '_', $this->value);
    }

    /**
     * Convert slug to camelCase.
     * Example: 'my-article-title-2024' → 'myArticleTitle2024'
     *
     * @return string camelCase version
     */
    public function toCamelCase(): string
    {
        $parts = explode('-', $this->value);
        $camelCase = array_shift($parts);

        foreach ($parts as $part) {
            $camelCase .= ucfirst($part);
        }

        return $camelCase;
    }

    /**
     * Convert slug to PascalCase.
     * Example: 'my-article-title-2024' → 'MyArticleTitle2024'
     *
     * @return string PascalCase version
     */
    public function toPascalCase(): string
    {
        return ucfirst($this->toCamelCase());
    }

    /**
     * Check if the slug is valid.
     *
     * @return bool True if valid
     */
    public function isValid(): bool
    {
        return (bool) preg_match(self::SLUG_REGEX, $this->value);
    }

    public function __toString(): string
    {
        return $this->value;
    }
}

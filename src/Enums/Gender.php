<?php

declare(strict_types=1);

namespace AndyDefer\PhpVo\Enums;

/**
 * Gender enumeration following ISO/IEC 5218 standard for representation of human sexes.
 *
 * This enum provides a standardized way to represent gender information in applications,
 * following the ISO/IEC 5218:2004 standard which defines codes for the representation
 * of human sexes.
 *
 * @see https://en.wikipedia.org/wiki/ISO/IEC_5218
 * @see https://www.iso.org/standard/36266.html
 *
 * ISO/IEC 5218 Standard Values:
 * - 0 = Not known
 * - 1 = Male
 * - 2 = Female
 * - 9 = Not applicable
 *
 * The standard is maintained by ISO/IEC JTC 1 and provides a common coding system
 * for the representation of human sexes in information systems.
 */
enum Gender: int
{
    /**
     * Gender is not known (ISO/IEC 5218: 0)
     * Used when gender information is unavailable or cannot be determined.
     */
    case UNKNOWN = 0;

    /**
     * Male gender (ISO/IEC 5218: 1)
     * Represents the male sex as defined by the standard.
     */
    case MALE = 1;

    /**
     * Female gender (ISO/IEC 5218: 2)
     * Represents the female sex as defined by the standard.
     */
    case FEMALE = 2;

    /**
     * Gender is not specified or not applicable (ISO/IEC 5218: 9)
     * Used when gender is not applicable to the context or the person
     * chooses not to specify.
     */
    case NOT_SPECIFIED = 9;

    /**
     * Get the human-readable label for the gender.
     *
     * @return string The display name of the gender
     */
    public function label(): string
    {
        return match ($this) {
            self::UNKNOWN => 'Unknown',
            self::MALE => 'Male',
            self::FEMALE => 'Female',
            self::NOT_SPECIFIED => 'Not specified',
        };
    }

    /**
     * Get all possible values as an array of integers.
     *
     * @return array<int> Array of integer values from the enum cases
     */
    public static function values(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }

    /**
     * Check if the gender is officially recognized in the ISO standard.
     *
     * According to ISO/IEC 5218, only MALE and FEMALE are considered
     * officially recognized sexes. UNKNOWN and NOT_SPECIFIED are
     * administrative codes.
     *
     * @return bool True if the gender is MALE or FEMALE, false otherwise
     */
    public function isOfficiallyRecognized(): bool
    {
        return match ($this) {
            self::MALE, self::FEMALE => true,
            default => false,
        };
    }
}

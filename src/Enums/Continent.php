<?php

// src/Enums/Continent.php

declare(strict_types=1);

namespace AndyDefer\PhpVo\Enums;

/**
 * Continent enumeration following ISO 3166-1 alpha-2 macro-regions.
 */
enum Continent: string
{
    case AFRICA = 'AF';
    case ANTARCTICA = 'AN';
    case ASIA = 'AS';
    case EUROPE = 'EU';
    case NORTH_AMERICA = 'NA';
    case OCEANIA = 'OC';
    case SOUTH_AMERICA = 'SA';

    /**
     * Get the human-readable label (French).
     */
    public function getLabel(): string
    {
        return match ($this) {
            self::AFRICA => 'Afrique',
            self::ANTARCTICA => 'Antarctique',
            self::ASIA => 'Asie',
            self::EUROPE => 'Europe',
            self::NORTH_AMERICA => 'Amérique du Nord',
            self::OCEANIA => 'Océanie',
            self::SOUTH_AMERICA => 'Amérique du Sud',
        };
    }

    /**
     * Resolve the continent from a timezone identifier.
     *
     * Uses the first segment of the IANA identifier (before the first "/").
     * Falls back to `null` for unknown regions.
     *
     * @example
     * Continent::fromTimezone('Africa/Kinshasa');       // self::AFRICA
     * Continent::fromTimezone('Europe/Paris');          // self::EUROPE
     * Continent::fromTimezone('America/New_York');      // self::NORTH_AMERICA
     * Continent::fromTimezone('Pacific/Auckland');      // self::OCEANIA
     */
    public static function fromTimezone(string $timezone): ?self
    {
        $region = explode('/', $timezone, 2)[0] ?? '';

        return match ($region) {
            'Africa' => self::AFRICA,
            'Antarctica' => self::ANTARCTICA,
            'Asia' => self::ASIA,
            'Europe' => self::EUROPE,
            'Australia', 'Pacific' => self::OCEANIA,
            'Atlantic' => self::EUROPE,
            'America' => self::NORTH_AMERICA,
            'Indian' => self::AFRICA,
            default => null,
        };
    }

    /**
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * @return array<string, string>
     */
    public static function labels(): array
    {
        $result = [];

        foreach (self::cases() as $case) {
            $result[$case->value] = $case->getLabel();
        }

        return $result;
    }
}

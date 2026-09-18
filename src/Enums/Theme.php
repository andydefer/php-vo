<?php

declare(strict_types=1);

namespace AndyDefer\PhpVo\Enums;

enum Theme: string
{
    case LIGHT = 'light';
    case DARK = 'dark';
    case SYSTEM = 'system';

    public function getLabel(): string
    {
        return match ($this) {
            self::LIGHT => 'Clair',
            self::DARK => 'Sombre',
            self::SYSTEM => 'Système',
        };
    }

    public function isLight(): bool
    {
        return $this === self::LIGHT;
    }

    public function isDark(): bool
    {
        return $this === self::DARK;
    }

    public function isSystem(): bool
    {
        return $this === self::SYSTEM;
    }

    /**
     * Resolve the effective theme for a given system preference.
     */
    public function resolve(bool $systemPrefersDark): self
    {
        if ($this === self::SYSTEM) {
            return $systemPrefersDark ? self::DARK : self::LIGHT;
        }

        return $this;
    }

    /**
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function default(): self
    {
        return self::SYSTEM;
    }
}

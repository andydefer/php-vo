<?php

declare(strict_types=1);

namespace AndyDefer\PhpVo\ValueObjects;

use AndyDefer\DomainStructures\Abstracts\AbstractValueObject;
use DateTime;
use DateTimeImmutable;
use DateTimeZone;
use InvalidArgumentException;

/**
 * Value Object representing an ISO 8601 datetime.
 *
 * Supports creation from multiple formats:
 * - ISO8601 string (2024-01-15T14:30:00+01:00) -> preserves timezone
 * - Database datetime (2024-01-15 14:30:00) -> treated as UTC
 * - Date only (2024-01-15) -> treated as UTC, time set to 00:00:00
 * - Null -> current datetime in UTC
 *
 * Storage is always ISO8601 format with proper timezone.
 *
 * @example
 * $date = DateTimeVO::from('2024-01-15T14:30:00+01:00');
 * echo $date->getValue(); // '2024-01-15T14:30:00+01:00'
 *
 * $date = DateTimeVO::from('2024-01-15 14:30:00'); // treated as UTC
 * echo $date->getValue(); // '2024-01-15T14:30:00+00:00'
 */
final class DateTimeVO extends AbstractValueObject
{
    private const ISO8601_FORMAT = 'Y-m-d\TH:i:sP';
    private const DATABASE_FORMAT = 'Y-m-d H:i:s';
    private const DATE_FORMAT = 'Y-m-d';
    private const TIME_FORMAT = 'H:i:s';

    public readonly string $value;

    /**
     * @param string|null $value Datetime string or null for current datetime
     */
    public function __construct(?string $value = null)
    {
        $dateTime = $this->normalizeToDateTimeImmutable($value);

        if ($dateTime === null) {
            throw new InvalidArgumentException('Invalid datetime value');
        }

        // Stocker au format ISO8601 avec son timezone d'origine
        $this->value = $dateTime->format(self::ISO8601_FORMAT);
    }

    /**
     * Normalize input to DateTimeImmutable.
     * Preserves original timezone when present.
     */
    private function normalizeToDateTimeImmutable(?string $value): ?DateTimeImmutable
    {
        if ($value === null) {
            return new DateTimeImmutable('now', new DateTimeZone('UTC'));
        }

        // Try ISO8601 format (Y-m-d\TH:i:sP) - preserves timezone
        $dateTime = DateTimeImmutable::createFromFormat(self::ISO8601_FORMAT, $value);
        if ($dateTime !== false) {
            return $dateTime;
        }

        // Try ISO8601 with Zulu time (Z) - convert to +00:00
        if (str_ends_with($value, 'Z')) {
            $dateTime = DateTimeImmutable::createFromFormat('Y-m-d\TH:i:s\Z', $value, new DateTimeZone('UTC'));
            if ($dateTime !== false) {
                return $dateTime;
            }
        }

        // Try database format (Y-m-d H:i:s) - assume UTC
        $dateTime = DateTimeImmutable::createFromFormat(self::DATABASE_FORMAT, $value, new DateTimeZone('UTC'));
        if ($dateTime !== false) {
            return $dateTime;
        }

        // Try date format (Y-m-d) -> time set to 00:00:00 UTC
        $dateTime = DateTimeImmutable::createFromFormat(self::DATE_FORMAT, $value, new DateTimeZone('UTC'));
        if ($dateTime !== false) {
            return $dateTime->setTime(0, 0, 0);
        }

        // Try generic parse as last resort
        try {
            return new DateTimeImmutable($value);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get the datetime as ISO8601 string (preserves original timezone).
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Get the datetime as DateTimeImmutable instance (preserves original timezone).
     */
    public function toDateTimeImmutable(): DateTimeImmutable
    {
        return new DateTimeImmutable($this->value);
    }

    /**
     * Get the datetime as DateTime instance (preserves original timezone).
     */
    public function toDateTime(): DateTime
    {
        return new DateTime($this->value);
    }

    /**
     * Get the datetime as UTC DateTimeImmutable instance.
     */
    public function toUtcDateTimeImmutable(): DateTimeImmutable
    {
        return (new DateTimeImmutable($this->value))->setTimezone(new DateTimeZone('UTC'));
    }

    /**
     * Get the datetime as database datetime string (Y-m-d H:i:s) in UTC.
     */
    public function toDateTimeString(): string
    {
        return $this->toUtcDateTimeImmutable()->format(self::DATABASE_FORMAT);
    }

    /**
     * Get the datetime as date string (Y-m-d) in original timezone.
     */
    public function toDateString(): string
    {
        return $this->toDateTimeImmutable()->format(self::DATE_FORMAT);
    }

    /**
     * Get the datetime as time string (H:i:s) in original timezone.
     */
    public function toTimeString(): string
    {
        return $this->toDateTimeImmutable()->format(self::TIME_FORMAT);
    }

    /**
     * Get Unix timestamp (always UTC).
     */
    public function toTimestamp(): int
    {
        return $this->toUtcDateTimeImmutable()->getTimestamp();
    }

    /**
     * Format the datetime using a custom format in original timezone.
     *
     * @param string $format PHP date format (e.g., 'd/m/Y H:i:s')
     */
    public function format(string $format): string
    {
        return $this->toDateTimeImmutable()->format($format);
    }

    /**
     * Check if this datetime is after another (comparison in UTC).
     */
    public function isAfter(self $other): bool
    {
        return $this->toUtcDateTimeImmutable() > $other->toUtcDateTimeImmutable();
    }

    /**
     * Check if this datetime is before another (comparison in UTC).
     */
    public function isBefore(self $other): bool
    {
        return $this->toUtcDateTimeImmutable() < $other->toUtcDateTimeImmutable();
    }

    /**
     * Check if this datetime is equal to another (comparison in UTC).
     */
    public function isEqual(self $other): bool
    {
        return $this->toUtcDateTimeImmutable() == $other->toUtcDateTimeImmutable();
    }

    /**
     * Check if the datetime is in the past (UTC comparison).
     */
    public function isPast(): bool
    {
        return $this->toUtcDateTimeImmutable() < new DateTimeImmutable('now', new DateTimeZone('UTC'));
    }

    /**
     * Check if the datetime is in the future (UTC comparison).
     */
    public function isFuture(): bool
    {
        return $this->toUtcDateTimeImmutable() > new DateTimeImmutable('now', new DateTimeZone('UTC'));
    }

    /**
     * Check if the datetime is today (in original timezone).
     */
    public function isToday(): bool
    {
        $now = new DateTimeImmutable('now', $this->toDateTimeImmutable()->getTimezone());

        return $this->toDateTimeImmutable()->format(self::DATE_FORMAT) === $now->format(self::DATE_FORMAT);
    }

    /**
     * Add an interval to the datetime.
     *
     * @param string $interval Interval specification (e.g., '1 day', '3 hours', '1 week')
     *
     * @return self New DateTimeVO with the interval added
     *
     * @throws InvalidArgumentException If interval is invalid
     */
    public function add(string $interval): self
    {
        try {
            $newDateTime = $this->toDateTimeImmutable()->modify('+' . ltrim($interval, '+'));

            if ($newDateTime === false) {
                throw new InvalidArgumentException("Invalid interval: {$interval}");
            }

            return new self($newDateTime->format(self::ISO8601_FORMAT));
        } catch (\DateMalformedStringException $e) {
            throw new InvalidArgumentException("Invalid interval: {$interval}", 0, $e);
        }
    }

    /**
     * Subtract an interval from the datetime.
     *a
     * @param string $interval Interval specification (e.g., '1 day', '3 hours', '1 week')
     *
     * @return self New DateTimeVO with the interval subtracted
     *
     * @throws InvalidArgumentException If interval is invalid
     */
    public function sub(string $interval): self
    {
        try {
            $newDateTime = $this->toDateTimeImmutable()->modify('-' . ltrim($interval, '-'));

            if ($newDateTime === false) {
                throw new InvalidArgumentException("Invalid interval: {$interval}");
            }

            return new self($newDateTime->format(self::ISO8601_FORMAT));
        } catch (\DateMalformedStringException $e) {
            throw new InvalidArgumentException("Invalid interval: {$interval}", 0, $e);
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }
}

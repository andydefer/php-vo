<?php

declare(strict_types=1);

namespace AndyDefer\PhpVo\ValueObjects;

use AndyDefer\DomainStructures\Abstracts\AbstractValueObject;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use DateTime;
use DateTimeImmutable;
use DateTimeZone;
use InvalidArgumentException;

/**
 * Immutable Value Object representing an ISO 8601 datetime.
 *
 * This class provides a type-safe, immutable wrapper around Carbon/CarbonInterface
 * with full support for timezone conversions, date arithmetic, and formatting.
 * All operations return new instances, preserving immutability.
 *
 * Supports multiple input formats:
 * - ISO 8601: `2024-01-15T14:30:00+01:00`
 * - Zulu time: `2024-01-15T14:30:00Z`
 * - Database: `2024-01-15 14:30:00`
 * - Date only: `2024-01-15` (time set to 00:00:00 UTC)
 * - Null: current datetime (respects Carbon::setTestNow() for testing)
 *
 * @example
 * $date = DateTimeVO::from('2024-01-15T14:30:00+01:00');
 * echo $date->getValue(); // '2024-01-15T14:30:00+01:00'
 *
 * $tomorrow = $date->addDays(1);
 * echo $tomorrow->toDateString(); // '2024-01-16'
 *
 * if ($date->isPast()) {
 *     // Handle past dates
 * }
 */
final class DateTimeVO extends AbstractValueObject
{
    private const ISO8601_FORMAT = 'Y-m-d\TH:i:sP';

    private const DATABASE_FORMAT = 'Y-m-d H:i:s';

    private const DATE_FORMAT = 'Y-m-d';

    private const TIME_FORMAT = 'H:i:s';

    private CarbonInterface $carbon;

    /**
     * Creates a new DateTimeVO instance.
     *
     * @param  string|null  $value  ISO 8601 datetime string, or null for current time
     *
     * @throws InvalidArgumentException If the datetime string is invalid
     */
    public function __construct(?string $value = null)
    {
        $this->carbon = $this->parseDateTime($value);
    }

    /**
     * Parses a datetime string into a Carbon instance.
     *
     * @param  string|null  $value  Datetime string to parse, or null for current time
     * @return CarbonInterface The parsed Carbon instance
     *
     * @throws InvalidArgumentException If the datetime string is invalid
     */
    private function parseDateTime(?string $value): CarbonInterface
    {
        if ($value === null) {
            return Carbon::now();
        }

        $parsers = [
            $this->parseIso8601(...),
            $this->parseZulu(...),
            $this->parseDatabase(...),
            $this->parseDateOnly(...),
            $this->parseGeneric(...),
        ];

        foreach ($parsers as $parser) {
            $result = $parser($value);
            if ($result !== null) {
                return $result;
            }
        }

        throw new InvalidArgumentException("Invalid datetime value: {$value}");
    }

    /**
     * Parses ISO 8601 format: Y-m-d\TH:i:sP
     */
    private function parseIso8601(string $value): ?CarbonInterface
    {
        try {
            $carbon = Carbon::createFromFormat(self::ISO8601_FORMAT, $value);

            return $carbon !== false ? $carbon : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Parses Zulu format: Y-m-d\TH:i:sZ
     */
    private function parseZulu(string $value): ?CarbonInterface
    {
        if (! str_ends_with($value, 'Z')) {
            return null;
        }

        try {
            $carbon = Carbon::createFromFormat('Y-m-d\TH:i:s\Z', $value, 'UTC');

            return $carbon !== false ? $carbon : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Parses database format: Y-m-d H:i:s
     */
    private function parseDatabase(string $value): ?CarbonInterface
    {
        try {
            $carbon = Carbon::createFromFormat(self::DATABASE_FORMAT, $value, 'UTC');

            return $carbon !== false ? $carbon : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Parses date-only format: Y-m-d (time set to 00:00:00 UTC)
     */
    private function parseDateOnly(string $value): ?CarbonInterface
    {
        try {
            $carbon = Carbon::createFromFormat(self::DATE_FORMAT, $value, 'UTC');

            return $carbon !== false ? $carbon->setTime(0, 0, 0) : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Generic fallback parser using Carbon's constructor.
     */
    private function parseGeneric(string $value): ?CarbonInterface
    {
        try {
            return new Carbon($value);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Creates a new instance from a Carbon instance.
     *
     * @param  CarbonInterface  $carbon  The Carbon instance to convert
     * @return self A new DateTimeVO instance
     */
    public static function fromCarbon(CarbonInterface $carbon): self
    {
        return new self($carbon->format(self::ISO8601_FORMAT));
    }

    /**
     * Creates a new instance with the current time.
     *
     * This method respects Carbon::setTestNow() for testing environments.
     *
     * @return self A new DateTimeVO instance set to the current time
     */
    public static function now(): self
    {
        return new self(null);
    }

    /**
     * Creates a new instance with today's date at midnight (00:00:00).
     *
     * @return self A new DateTimeVO instance set to today's date at midnight
     */
    public static function today(): self
    {
        return self::fromCarbon(Carbon::today());
    }

    /**
     * Creates a new instance with a specific date and time.
     *
     * @param  int  $year  The year (e.g., 2024)
     * @param  int  $month  The month (1-12)
     * @param  int  $day  The day (1-31)
     * @param  int  $hour  The hour (0-23, default: 0)
     * @param  int  $minute  The minute (0-59, default: 0)
     * @param  int  $second  The second (0-59, default: 0)
     * @param  string|null  $timezone  Timezone name (e.g., 'Europe/Paris'), or null for UTC
     * @return self A new DateTimeVO instance
     */
    public static function create(
        int $year,
        int $month,
        int $day,
        int $hour = 0,
        int $minute = 0,
        int $second = 0,
        ?string $timezone = null
    ): self {
        $carbon = Carbon::create($year, $month, $day, $hour, $minute, $second, $timezone ?? 'UTC');

        return self::fromCarbon($carbon);
    }

    /**
     * Returns the underlying Carbon instance.
     *
     * This method is intended for advanced use cases where direct Carbon access is needed.
     *
     * @return CarbonInterface The internal Carbon instance
     */
    public function getCarbon(): CarbonInterface
    {
        return $this->carbon;
    }

    /**
     * Gets the ISO 8601 string representation.
     *
     * @return string The ISO 8601 datetime string
     */
    public function getValue(): string
    {
        return $this->carbon->format(self::ISO8601_FORMAT);
    }

    /**
     * Converts to a native DateTime instance.
     *
     * @return DateTime The DateTime representation
     */
    public function toDateTime(): DateTime
    {
        return $this->carbon->toDateTime();
    }

    /**
     * Converts to a native DateTimeImmutable instance.
     *
     * @return DateTimeImmutable The DateTimeImmutable representation
     */
    public function toDateTimeImmutable(): DateTimeImmutable
    {
        return $this->carbon->toDateTimeImmutable();
    }

    /**
     * Converts to a UTC DateTimeImmutable instance.
     *
     * @return DateTimeImmutable The UTC DateTimeImmutable representation
     */
    public function toUtcDateTimeImmutable(): DateTimeImmutable
    {
        return $this->carbon->copy()->utc()->toDateTimeImmutable();
    }

    /**
     * Converts to database format: Y-m-d H:i:s in UTC.
     *
     * @return string The datetime as a database string in UTC
     */
    public function toDateTimeString(): string
    {
        return $this->carbon->copy()->utc()->format(self::DATABASE_FORMAT);
    }

    /**
     * Gets the date part: Y-m-d.
     *
     * @return string The date in Y-m-d format
     */
    public function toDateString(): string
    {
        return $this->carbon->format(self::DATE_FORMAT);
    }

    /**
     * Gets the time part: H:i:s.
     *
     * @return string The time in H:i:s format
     */
    public function toTimeString(): string
    {
        return $this->carbon->format(self::TIME_FORMAT);
    }

    /**
     * Gets the Unix timestamp.
     *
     * @return int The Unix timestamp
     */
    public function toTimestamp(): int
    {
        return $this->carbon->timestamp;
    }

    /**
     * Formats the datetime using a custom format.
     *
     * @param  string  $format  PHP date format (e.g., 'd/m/Y H:i:s')
     * @return string The formatted date string
     */
    public function format(string $format): string
    {
        return $this->carbon->format($format);
    }

    /**
     * Checks if this datetime is after another datetime.
     *
     * @param  self  $other  The datetime to compare against
     * @return bool True if this datetime is after the other
     */
    public function isAfter(self $other): bool
    {
        return $this->carbon->gt($other->carbon);
    }

    /**
     * Checks if this datetime is before another datetime.
     *
     * @param  self  $other  The datetime to compare against
     * @return bool True if this datetime is before the other
     */
    public function isBefore(self $other): bool
    {
        return $this->carbon->lt($other->carbon);
    }

    /**
     * Checks if this datetime is equal to another datetime.
     *
     * @param  self  $other  The datetime to compare against
     * @return bool True if the datetimes are equal
     */
    public function isEqual(self $other): bool
    {
        return $this->carbon->eq($other->carbon);
    }

    /**
     * Checks if this datetime is in the past.
     *
     * @return bool True if the datetime is in the past
     */
    public function isPast(): bool
    {
        return $this->carbon->isPast();
    }

    /**
     * Checks if this datetime is in the future.
     *
     * @return bool True if the datetime is in the future
     */
    public function isFuture(): bool
    {
        return $this->carbon->isFuture();
    }

    /**
     * Checks if this datetime is today.
     *
     * @return bool True if the datetime is today
     */
    public function isToday(): bool
    {
        return $this->carbon->isToday();
    }

    /**
     * Checks if this datetime is tomorrow.
     *
     * @return bool True if the datetime is tomorrow
     */
    public function isTomorrow(): bool
    {
        return $this->carbon->isTomorrow();
    }

    /**
     * Checks if this datetime is yesterday.
     *
     * @return bool True if the datetime is yesterday
     */
    public function isYesterday(): bool
    {
        return $this->carbon->isYesterday();
    }

    /**
     * Adds an interval to the datetime.
     *
     * @param  string  $interval  Interval specification (e.g., '1 day', '3 hours', '1 week')
     * @return self A new DateTimeVO instance with the interval added
     *
     * @throws InvalidArgumentException If the interval is invalid
     */
    public function add(string $interval): self
    {
        $newCarbon = $this->carbon->copy()->add($interval);

        return self::fromCarbon($newCarbon);
    }

    /**
     * Subtracts an interval from the datetime.
     *
     * @param  string  $interval  Interval specification (e.g., '1 day', '3 hours', '1 week')
     * @return self A new DateTimeVO instance with the interval subtracted
     *
     * @throws InvalidArgumentException If the interval is invalid
     */
    public function sub(string $interval): self
    {
        $newCarbon = $this->carbon->copy()->sub($interval);

        return self::fromCarbon($newCarbon);
    }

    /**
     * Adds a specified number of days.
     *
     * @param  int  $days  Number of days to add
     * @return self A new DateTimeVO instance
     */
    public function addDays(int $days): self
    {
        $newCarbon = $this->carbon->copy()->addDays($days);

        return self::fromCarbon($newCarbon);
    }

    /**
     * Subtracts a specified number of days.
     *
     * @param  int  $days  Number of days to subtract
     * @return self A new DateTimeVO instance
     */
    public function subDays(int $days): self
    {
        $newCarbon = $this->carbon->copy()->subDays($days);

        return self::fromCarbon($newCarbon);
    }

    /**
     * Adds a specified number of hours.
     *
     * @param  int  $hours  Number of hours to add
     * @return self A new DateTimeVO instance
     */
    public function addHours(int $hours): self
    {
        $newCarbon = $this->carbon->copy()->addHours($hours);

        return self::fromCarbon($newCarbon);
    }

    /**
     * Subtracts a specified number of hours.
     *
     * @param  int  $hours  Number of hours to subtract
     * @return self A new DateTimeVO instance
     */
    public function subHours(int $hours): self
    {
        $newCarbon = $this->carbon->copy()->subHours($hours);

        return self::fromCarbon($newCarbon);
    }

    /**
     * Adds a specified number of minutes.
     *
     * @param  int  $minutes  Number of minutes to add
     * @return self A new DateTimeVO instance
     */
    public function addMinutes(int $minutes): self
    {
        $newCarbon = $this->carbon->copy()->addMinutes($minutes);

        return self::fromCarbon($newCarbon);
    }

    /**
     * Subtracts a specified number of minutes.
     *
     * @param  int  $minutes  Number of minutes to subtract
     * @return self A new DateTimeVO instance
     */
    public function subMinutes(int $minutes): self
    {
        $newCarbon = $this->carbon->copy()->subMinutes($minutes);

        return self::fromCarbon($newCarbon);
    }

    /**
     * Adds a specified number of months.
     *
     * @param  int  $months  Number of months to add
     * @return self A new DateTimeVO instance
     */
    public function addMonths(int $months): self
    {
        $newCarbon = $this->carbon->copy()->addMonths($months);

        return self::fromCarbon($newCarbon);
    }

    /**
     * Subtracts a specified number of months.
     *
     * @param  int  $months  Number of months to subtract
     * @return self A new DateTimeVO instance
     */
    public function subMonths(int $months): self
    {
        $newCarbon = $this->carbon->copy()->subMonths($months);

        return self::fromCarbon($newCarbon);
    }

    /**
     * Adds a specified number of years.
     *
     * @param  int  $years  Number of years to add
     * @return self A new DateTimeVO instance
     */
    public function addYears(int $years): self
    {
        $newCarbon = $this->carbon->copy()->addYears($years);

        return self::fromCarbon($newCarbon);
    }

    /**
     * Subtracts a specified number of years.
     *
     * @param  int  $years  Number of years to subtract
     * @return self A new DateTimeVO instance
     */
    public function subYears(int $years): self
    {
        $newCarbon = $this->carbon->copy()->subYears($years);

        return self::fromCarbon($newCarbon);
    }

    /**
     * Gets the absolute difference in seconds between two datetimes.
     *
     * @param  self  $other  The datetime to compare against
     * @return float The difference in seconds (always positive)
     */
    public function diffInSeconds(self $other): float
    {
        return $this->carbon->diffInSeconds($other->carbon, true);
    }

    /**
     * Gets the absolute difference in minutes between two datetimes.
     *
     * @param  self  $other  The datetime to compare against
     * @return float The difference in minutes (always positive)
     */
    public function diffInMinutes(self $other): float
    {
        return $this->carbon->diffInMinutes($other->carbon, true);
    }

    /**
     * Gets the absolute difference in hours between two datetimes.
     *
     * @param  self  $other  The datetime to compare against
     * @return float The difference in hours (always positive)
     */
    public function diffInHours(self $other): float
    {
        return $this->carbon->diffInHours($other->carbon, true);
    }

    /**
     * Gets the absolute difference in days between two datetimes.
     *
     * @param  self  $other  The datetime to compare against
     * @return float The difference in days (always positive)
     */
    public function diffInDays(self $other): float
    {
        return $this->carbon->diffInDays($other->carbon, true);
    }

    /**
     * Gets the absolute difference in months between two datetimes.
     *
     * @param  self  $other  The datetime to compare against
     * @return float The difference in months (always positive)
     */
    public function diffInMonths(self $other): float
    {
        return $this->carbon->diffInMonths($other->carbon, true);
    }

    /**
     * Gets the absolute difference in years between two datetimes.
     *
     * @param  self  $other  The datetime to compare against
     * @return float The difference in years (always positive)
     */
    public function diffInYears(self $other): float
    {
        return $this->carbon->diffInYears($other->carbon, true);
    }

    /**
     * Converts the datetime to UTC timezone.
     *
     * @return self A new DateTimeVO instance in UTC
     */
    public function toUtc(): self
    {
        $newCarbon = $this->carbon->copy()->utc();

        return self::fromCarbon($newCarbon);
    }

    /**
     * Converts the datetime to a specific timezone.
     *
     * @param  string  $timezone  Timezone name (e.g., 'Europe/Paris', 'America/New_York')
     * @return self A new DateTimeVO instance in the specified timezone
     */
    public function toTimezone(string $timezone): self
    {
        $newCarbon = $this->carbon->copy()->timezone($timezone);

        return self::fromCarbon($newCarbon);
    }

    /**
     * Gets the timezone of the datetime.
     *
     * @return DateTimeZone The datetime's timezone
     */
    public function getTimezone(): DateTimeZone
    {
        return $this->carbon->getTimezone();
    }

    /**
     * Checks if the datetime has an explicit timezone.
     *
     * @return bool True if the datetime has a timezone, false otherwise
     */
    public function hasTimezone(): bool
    {
        return $this->carbon->getTimezone() !== null;
    }

    /**
     * Gets the year component.
     *
     * @return int The year
     */
    public function getYear(): int
    {
        return $this->carbon->year;
    }

    /**
     * Gets the month component (1-12).
     *
     * @return int The month
     */
    public function getMonth(): int
    {
        return $this->carbon->month;
    }

    /**
     * Gets the day component (1-31).
     *
     * @return int The day
     */
    public function getDay(): int
    {
        return $this->carbon->day;
    }

    /**
     * Gets the hour component (0-23).
     *
     * @return int The hour
     */
    public function getHour(): int
    {
        return $this->carbon->hour;
    }

    /**
     * Gets the minute component (0-59).
     *
     * @return int The minute
     */
    public function getMinute(): int
    {
        return $this->carbon->minute;
    }

    /**
     * Gets the second component (0-59).
     *
     * @return int The second
     */
    public function getSecond(): int
    {
        return $this->carbon->second;
    }

    /**
     * Gets the day of the week (1 = Monday, 7 = Sunday).
     *
     * @return int The ISO-8601 day of week
     */
    public function getDayOfWeek(): int
    {
        return $this->carbon->dayOfWeekIso;
    }

    /**
     * Gets the week number of the year.
     *
     * @return int The ISO-8601 week number
     */
    public function getWeekOfYear(): int
    {
        return $this->carbon->weekOfYear;
    }

    /**
     * Returns a new instance with the time set to the start of the day (00:00:00).
     *
     * @return self A new DateTimeVO instance at midnight
     */
    public function startOfDay(): self
    {
        $newCarbon = $this->carbon->copy()->startOfDay();

        return self::fromCarbon($newCarbon);
    }

    /**
     * Returns a new instance with the time set to the end of the day (23:59:59).
     *
     * @return self A new DateTimeVO instance at the end of the day
     */
    public function endOfDay(): self
    {
        $newCarbon = $this->carbon->copy()->endOfDay();

        return self::fromCarbon($newCarbon);
    }

    /**
     * Returns a new instance with the date set to the start of the month.
     *
     * @return self A new DateTimeVO instance at the start of the month
     */
    public function startOfMonth(): self
    {
        $newCarbon = $this->carbon->copy()->startOfMonth();

        return self::fromCarbon($newCarbon);
    }

    /**
     * Returns a new instance with the date set to the end of the month.
     *
     * @return self A new DateTimeVO instance at the end of the month
     */
    public function endOfMonth(): self
    {
        $newCarbon = $this->carbon->copy()->endOfMonth();

        return self::fromCarbon($newCarbon);
    }

    /**
     * Returns a new instance with the date set to the start of the year.
     *
     * @return self A new DateTimeVO instance at the start of the year
     */
    public function startOfYear(): self
    {
        $newCarbon = $this->carbon->copy()->startOfYear();

        return self::fromCarbon($newCarbon);
    }

    /**
     * Returns a new instance with the date set to the end of the year.
     *
     * @return self A new DateTimeVO instance at the end of the year
     */
    public function endOfYear(): self
    {
        $newCarbon = $this->carbon->copy()->endOfYear();

        return self::fromCarbon($newCarbon);
    }

    /**
     * Magic method to get the ISO 8601 string representation.
     *
     * @return string The ISO 8601 datetime string
     */
    public function __toString(): string
    {
        return $this->getValue();
    }
}

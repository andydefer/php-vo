<?php

declare(strict_types=1);

namespace AndyDefer\PhpVo\Tests\Unit\ValueObjects;

use AndyDefer\PhpVo\ValueObjects\DateTimeVO;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class DateTimeVOTest extends TestCase
{
    // ==================== CREATE FROM STRING TESTS ====================

    public function test_create_from_iso8601_string(): void
    {
        $date = DateTimeVO::from('2024-01-15T14:30:00+01:00');

        $this->assertSame('2024-01-15T14:30:00+01:00', $date->getValue());
    }

    public function test_create_from_iso8601_utc_string(): void
    {
        $date = DateTimeVO::from('2024-01-15T14:30:00Z');

        $this->assertSame('2024-01-15T14:30:00+00:00', $date->getValue());
    }

    public function test_create_from_database_datetime_string(): void
    {
        $date = DateTimeVO::from('2024-01-15 14:30:00');

        $this->assertSame('2024-01-15T14:30:00+00:00', $date->getValue());
    }

    public function test_create_from_date_only_string(): void
    {
        $date = DateTimeVO::from('2024-01-15');

        $this->assertSame('2024-01-15T00:00:00+00:00', $date->getValue());
    }

    public function test_create_from_null_returns_current_datetime(): void
    {
        $date = DateTimeVO::from(null);

        $this->assertMatchesRegularExpression('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}\+\d{2}:\d{2}$/', $date->getValue());
    }

    public function test_create_from_invalid_string_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid datetime value');

        DateTimeVO::from('invalid-date');
    }

    public function test_create_from_existing_instance_returns_same_instance(): void
    {
        $original = DateTimeVO::from('2024-01-15T14:30:00+01:00');
        $duplicate = DateTimeVO::from($original);

        $this->assertSame($original, $duplicate);
    }

    // ==================== GET VALUE TESTS ====================

    public function test_get_value_returns_iso8601_string(): void
    {
        $date = DateTimeVO::from('2024-01-15 14:30:00');

        $this->assertSame('2024-01-15T14:30:00+00:00', $date->getValue());
        $this->assertIsString($date->getValue());
    }

    // ==================== CONVERSION METHODS TESTS ====================

    public function test_to_date_time_string_returns_database_format(): void
    {
        $date = DateTimeVO::from('2024-01-15T14:30:00+00:00');

        $this->assertSame('2024-01-15 14:30:00', $date->toDateTimeString());
    }

    public function test_to_date_string_returns_date_only(): void
    {
        $date = DateTimeVO::from('2024-01-15T14:30:00+01:00');

        $this->assertSame('2024-01-15', $date->toDateString());
    }

    public function test_to_time_string_returns_time_only(): void
    {
        $date = DateTimeVO::from('2024-01-15T14:30:00+00:00');

        $this->assertSame('14:30:00', $date->toTimeString());
    }

    public function test_to_timestamp_returns_unix_timestamp(): void
    {
        $date = DateTimeVO::from('2024-01-15T14:30:00+00:00');

        $this->assertSame(1705329000, $date->toTimestamp());
        $this->assertIsInt($date->toTimestamp());
    }

    public function test_to_date_time_returns_date_time_instance(): void
    {
        $date = DateTimeVO::from('2024-01-15T14:30:00+00:00');
        $dateTime = $date->toDateTime();

        $this->assertInstanceOf(\DateTime::class, $dateTime);
        $this->assertSame('2024-01-15 14:30:00', $dateTime->format('Y-m-d H:i:s'));
    }

    public function test_to_date_time_immutable_returns_date_time_immutable_instance(): void
    {
        $date = DateTimeVO::from('2024-01-15T14:30:00+00:00');
        $dateTime = $date->toDateTimeImmutable();

        $this->assertInstanceOf(\DateTimeImmutable::class, $dateTime);
        $this->assertSame('2024-01-15 14:30:00', $dateTime->format('Y-m-d H:i:s'));
    }

    public function test_to_utc_date_time_immutable_converts_to_utc(): void
    {
        $date = DateTimeVO::from('2024-01-15T14:30:00+01:00');
        $utcDateTime = $date->toUtcDateTimeImmutable();

        $this->assertSame('UTC', $utcDateTime->getTimezone()->getName());
        $this->assertSame('2024-01-15 13:30:00', $utcDateTime->format('Y-m-d H:i:s'));
    }

    // ==================== FORMAT METHOD TESTS ====================

    public function test_format_returns_custom_format(): void
    {
        $date = DateTimeVO::from('2024-01-15T14:30:00+00:00');

        $this->assertSame('15/01/2024', $date->format('d/m/Y'));
        $this->assertSame('14:30', $date->format('H:i'));
        $this->assertSame('January', $date->format('F'));
        $this->assertSame('2024', $date->format('Y'));
    }

    // ==================== COMPARISON METHODS TESTS ====================

    public function test_is_after_returns_true_when_date_is_after(): void
    {
        $date1 = DateTimeVO::from('2024-01-15T14:30:00+00:00');
        $date2 = DateTimeVO::from('2024-01-14T14:30:00+00:00');

        $this->assertTrue($date1->isAfter($date2));
        $this->assertFalse($date2->isAfter($date1));
    }

    public function test_is_before_returns_true_when_date_is_before(): void
    {
        $date1 = DateTimeVO::from('2024-01-14T14:30:00+00:00');
        $date2 = DateTimeVO::from('2024-01-15T14:30:00+00:00');

        $this->assertTrue($date1->isBefore($date2));
        $this->assertFalse($date2->isBefore($date1));
    }

    public function test_is_equal_returns_true_when_dates_are_equal(): void
    {
        $date1 = DateTimeVO::from('2024-01-15T14:30:00+00:00');
        $date2 = DateTimeVO::from('2024-01-15T14:30:00+00:00');

        $this->assertTrue($date1->isEqual($date2));
    }

    public function test_is_equal_returns_true_for_same_moment_different_timezones(): void
    {
        $paris = DateTimeVO::from('2024-01-15T14:30:00+01:00');
        $london = DateTimeVO::from('2024-01-15T13:30:00+00:00');

        $this->assertTrue($paris->isEqual($london));
    }

    public function test_is_equal_returns_false_when_dates_are_different(): void
    {
        $date1 = DateTimeVO::from('2024-01-15T14:30:00+00:00');
        $date2 = DateTimeVO::from('2024-01-16T14:30:00+00:00');

        $this->assertFalse($date1->isEqual($date2));
    }

    // ==================== STATE CHECK METHODS TESTS ====================

    public function test_is_past_returns_true_for_date_in_past(): void
    {
        $date = DateTimeVO::from('2020-01-01T00:00:00+00:00');

        $this->assertTrue($date->isPast());
    }

    public function test_is_past_returns_false_for_date_in_future(): void
    {
        $date = DateTimeVO::from('2100-01-01T00:00:00+00:00');

        $this->assertFalse($date->isPast());
    }

    public function test_is_future_returns_true_for_date_in_future(): void
    {
        $date = DateTimeVO::from('2100-01-01T00:00:00+00:00');

        $this->assertTrue($date->isFuture());
    }

    public function test_is_future_returns_false_for_date_in_past(): void
    {
        $date = DateTimeVO::from('2020-01-01T00:00:00+00:00');

        $this->assertFalse($date->isFuture());
    }

    public function test_is_today_returns_true_for_today(): void
    {
        $today = (new \DateTimeImmutable('now', new \DateTimeZone('UTC')))->format('Y-m-d');
        $date = DateTimeVO::from($today);

        $this->assertTrue($date->isToday());
    }

    public function test_is_today_returns_false_for_yesterday(): void
    {
        $yesterday = (new \DateTimeImmutable('-1 day', new \DateTimeZone('UTC')))->format('Y-m-d');
        $date = DateTimeVO::from($yesterday);

        $this->assertFalse($date->isToday());
    }

    // ==================== ARITHMETIC METHODS TESTS ====================

    public function test_add_days(): void
    {
        $date = DateTimeVO::from('2024-01-15T00:00:00+00:00');
        $result = $date->add('1 day');

        $this->assertSame('2024-01-16T00:00:00+00:00', $result->getValue());
    }

    public function test_add_days_using_plus_prefix(): void
    {
        $date = DateTimeVO::from('2024-01-15T00:00:00+00:00');
        $result = $date->add('+1 day');

        $this->assertSame('2024-01-16T00:00:00+00:00', $result->getValue());
    }

    public function test_add_hours(): void
    {
        $date = DateTimeVO::from('2024-01-15T00:00:00+00:00');
        $result = $date->add('3 hours');

        $this->assertSame('2024-01-15T03:00:00+00:00', $result->getValue());
    }

    public function test_add_weeks(): void
    {
        $date = DateTimeVO::from('2024-01-15T00:00:00+00:00');
        $result = $date->add('1 week');

        $this->assertSame('2024-01-22T00:00:00+00:00', $result->getValue());
    }

    public function test_add_months(): void
    {
        $date = DateTimeVO::from('2024-01-15T00:00:00+00:00');
        $result = $date->add('1 month');

        $this->assertSame('2024-02-15T00:00:00+00:00', $result->getValue());
    }

    public function test_sub_days(): void
    {
        $date = DateTimeVO::from('2024-01-15T00:00:00+00:00');
        $result = $date->sub('1 day');

        $this->assertSame('2024-01-14T00:00:00+00:00', $result->getValue());
    }

    public function test_sub_hours(): void
    {
        $date = DateTimeVO::from('2024-01-15T12:00:00+00:00');
        $result = $date->sub('3 hours');

        $this->assertSame('2024-01-15T09:00:00+00:00', $result->getValue());
    }

    public function test_add_with_invalid_interval_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid interval');

        $date = DateTimeVO::from('2024-01-15T00:00:00+00:00');
        $date->add('invalid-interval');
    }

    public function test_sub_with_invalid_interval_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid interval');

        $date = DateTimeVO::from('2024-01-15T00:00:00+00:00');
        $date->sub('invalid-interval');
    }

    // ==================== IMMUTABILITY TESTS ====================

    public function test_immutability_add(): void
    {
        $original = DateTimeVO::from('2024-01-15T00:00:00+00:00');
        $new = $original->add('1 day');

        $this->assertNotSame($original, $new);
        $this->assertSame('2024-01-15T00:00:00+00:00', $original->getValue());
        $this->assertSame('2024-01-16T00:00:00+00:00', $new->getValue());
    }

    public function test_immutability_sub(): void
    {
        $original = DateTimeVO::from('2024-01-15T00:00:00+00:00');
        $new = $original->sub('1 day');

        $this->assertNotSame($original, $new);
        $this->assertSame('2024-01-15T00:00:00+00:00', $original->getValue());
        $this->assertSame('2024-01-14T00:00:00+00:00', $new->getValue());
    }

    // ==================== CHAINING TESTS ====================

    public function test_chaining_operations(): void
    {
        $result = DateTimeVO::from('2024-01-15T00:00:00+00:00')
            ->add('3 days')
            ->add('5 hours')
            ->sub('1 day');

        $this->assertSame('2024-01-17T05:00:00+00:00', $result->getValue());
    }

    // ==================== STRING REPRESENTATION TESTS ====================

    public function test_to_string_magic_method_returns_iso8601(): void
    {
        $date = DateTimeVO::from('2024-01-15T14:30:00+01:00');

        $this->assertSame('2024-01-15T14:30:00+01:00', (string) $date);
    }

    // ==================== EDGE CASES TESTS ====================

    public function test_handles_different_timezones_correctly(): void
    {
        $date = DateTimeVO::from('2024-01-15T14:30:00+01:00');

        // Le timezone doit être préservé dans getValue()
        $this->assertSame('2024-01-15T14:30:00+01:00', $date->getValue());

        // En UTC, cela devient 13:30
        $this->assertSame('2024-01-15 13:30:00', $date->toDateTimeString());
    }

    public function test_handles_end_of_month_correctly(): void
    {
        $date = DateTimeVO::from('2024-01-31T00:00:00+00:00');
        $result = $date->add('1 month');

        // PHP behavior: 31 Jan + 1 month = 2 March
        $this->assertSame('2024-03-02T00:00:00+00:00', $result->getValue());
    }

    public function test_handles_leap_year_correctly(): void
    {
        $date = DateTimeVO::from('2024-02-28T00:00:00+00:00');
        $result = $date->add('1 day');

        $this->assertSame('2024-02-29T00:00:00+00:00', $result->getValue());
    }

    public function test_handles_timezone_aware_comparison(): void
    {
        $paris = DateTimeVO::from('2024-01-15T14:30:00+01:00');
        $london = DateTimeVO::from('2024-01-15T13:30:00+00:00');

        // Ces deux dates représentent le même moment
        $this->assertTrue($paris->isEqual($london));
        $this->assertFalse($paris->isAfter($london));
        $this->assertFalse($paris->isBefore($london));
    }
}

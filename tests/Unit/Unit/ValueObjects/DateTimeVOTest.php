<?php

declare(strict_types=1);

namespace AndyDefer\PhpVo\Tests\Unit\ValueObjects;

use AndyDefer\PhpVo\ValueObjects\DateTimeVO;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use DateTimeZone;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class DateTimeVOTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Carbon::setTestNow(null);
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow(null);
        parent::tearDown();
    }

    // ==================== CREATION TESTS ====================

    public function test_it_parses_iso8601_string(): void
    {
        $input = '2024-01-15T14:30:00+01:00';
        $date = DateTimeVO::from($input);

        // Maintenant getValue() retourne le format base de données
        $this->assertSame('2024-01-15 13:30:00', $date->getValue());
        // Mais on peut toujours récupérer le format ISO avec toIso8601String()
        $this->assertSame('2024-01-15T14:30:00+01:00', $date->toIso8601String());
    }

    public function test_it_parses_iso8601_utc_string(): void
    {
        $input = '2024-01-15T14:30:00Z';
        $date = DateTimeVO::from($input);

        $this->assertSame('2024-01-15 14:30:00', $date->getValue());
        $this->assertSame('2024-01-15T14:30:00+00:00', $date->toIso8601String());
    }

    public function test_it_parses_database_datetime_string(): void
    {
        $input = '2024-01-15 14:30:00';
        $date = DateTimeVO::from($input);

        $this->assertSame('2024-01-15 14:30:00', $date->getValue());
        $this->assertSame('2024-01-15T14:30:00+00:00', $date->toIso8601String());
    }

    public function test_it_parses_date_only_string(): void
    {
        $input = '2024-01-15';
        $date = DateTimeVO::from($input);

        $this->assertSame('2024-01-15 00:00:00', $date->getValue());
        $this->assertSame('2024-01-15T00:00:00+00:00', $date->toIso8601String());
    }

    public function test_it_returns_current_datetime_when_null_provided(): void
    {
        Carbon::setTestNow(Carbon::create(2024, 1, 15, 14, 30, 0));
        $date = DateTimeVO::from(null);

        $this->assertSame('2024-01-15 14:30:00', $date->getValue());
        $this->assertSame('2024-01-15T14:30:00+00:00', $date->toIso8601String());
    }

    public function test_it_throws_exception_for_invalid_string(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid datetime value');
        DateTimeVO::from('invalid-date');
    }

    public function test_it_returns_same_instance_when_created_from_existing(): void
    {
        $original = DateTimeVO::from('2024-01-15T14:30:00+01:00');
        $duplicate = DateTimeVO::from($original);

        $this->assertSame($original, $duplicate);
    }

    // ==================== FACTORY METHODS TESTS ====================

    public function test_now_returns_current_datetime(): void
    {
        Carbon::setTestNow(Carbon::create(2024, 1, 15, 14, 30, 0));
        $now = DateTimeVO::now();

        $this->assertSame('2024-01-15 14:30:00', $now->getValue());
        $this->assertSame('2024-01-15T14:30:00+00:00', $now->toIso8601String());
    }

    public function test_today_returns_midnight_of_current_day(): void
    {
        Carbon::setTestNow(Carbon::create(2024, 1, 15, 14, 30, 0));
        $today = DateTimeVO::today();

        $this->assertSame('2024-01-15 00:00:00', $today->getValue());
        $this->assertSame('2024-01-15T00:00:00+00:00', $today->toIso8601String());
    }

    public function test_create_builds_datetime_from_parts(): void
    {
        $date = DateTimeVO::create(2024, 1, 15, 14, 30, 0);

        $this->assertSame('2024-01-15 14:30:00', $date->getValue());
        $this->assertSame('2024-01-15T14:30:00+00:00', $date->toIso8601String());
    }

    public function test_create_handles_timezone_parameter(): void
    {
        $date = DateTimeVO::create(2024, 1, 15, 14, 30, 0, 'Europe/Paris');

        $this->assertSame('2024-01-15 13:30:00', $date->getValue());
        $this->assertSame('2024-01-15T14:30:00+01:00', $date->toIso8601String());
    }

    // ==================== VALUE RETRIEVAL TESTS ====================

    public function test_get_value_returns_database_format(): void
    {
        $date = DateTimeVO::from('2024-01-15 14:30:00');
        $value = $date->getValue();

        $this->assertSame('2024-01-15 14:30:00', $value);
        $this->assertIsString($value);
    }

    public function test_to_iso8601_returns_iso_format(): void
    {
        $date = DateTimeVO::from('2024-01-15 14:30:00');
        $value = $date->toIso8601String();

        $this->assertSame('2024-01-15T14:30:00+00:00', $value);
        $this->assertIsString($value);
    }

    // ==================== CONVERSION TESTS ====================

    public function test_it_converts_to_database_string(): void
    {
        $date = DateTimeVO::from('2024-01-15T14:30:00+00:00');
        $result = $date->toDateTimeString();

        $this->assertSame('2024-01-15 14:30:00', $result);
    }

    public function test_it_converts_to_date_string(): void
    {
        $date = DateTimeVO::from('2024-01-15T14:30:00+01:00');
        $result = $date->toDateString();

        $this->assertSame('2024-01-15', $result);
    }

    public function test_it_converts_to_time_string(): void
    {
        $date = DateTimeVO::from('2024-01-15T14:30:00+00:00');
        $result = $date->toTimeString();

        $this->assertSame('14:30:00', $result);
    }

    public function test_it_converts_to_unix_timestamp(): void
    {
        $date = DateTimeVO::from('2024-01-15T14:30:00+00:00');
        $expected = 1705329000;

        $result = $date->toTimestamp();

        $this->assertSame($expected, $result);
        $this->assertIsInt($result);
    }

    public function test_it_converts_to_native_datetime_instance(): void
    {
        $date = DateTimeVO::from('2024-01-15T14:30:00+00:00');
        $dateTime = $date->toDateTime();

        $this->assertInstanceOf(\DateTime::class, $dateTime);
        $this->assertSame('2024-01-15 14:30:00', $dateTime->format('Y-m-d H:i:s'));
    }

    public function test_it_converts_to_native_datetime_immutable_instance(): void
    {
        $date = DateTimeVO::from('2024-01-15T14:30:00+00:00');
        $dateTime = $date->toDateTimeImmutable();

        $this->assertInstanceOf(\DateTimeImmutable::class, $dateTime);
        $this->assertSame('2024-01-15 14:30:00', $dateTime->format('Y-m-d H:i:s'));
    }

    public function test_it_converts_to_utc_datetime_immutable(): void
    {
        $date = DateTimeVO::from('2024-01-15T14:30:00+01:00');
        $utcDateTime = $date->toUtcDateTimeImmutable();

        $this->assertSame('UTC', $utcDateTime->getTimezone()->getName());
        $this->assertSame('2024-01-15 13:30:00', $utcDateTime->format('Y-m-d H:i:s'));
    }

    // ==================== FORMATTING TESTS ====================

    public function test_it_formats_with_custom_format(): void
    {
        $date = DateTimeVO::from('2024-01-15T14:30:00+00:00');

        $this->assertSame('15/01/2024', $date->format('d/m/Y'));
        $this->assertSame('14:30', $date->format('H:i'));
        $this->assertSame('January', $date->format('F'));
        $this->assertSame('2024', $date->format('Y'));
    }

    // ==================== COMPARISON TESTS ====================

    public function test_is_after_returns_true_when_date_is_later(): void
    {
        $later = DateTimeVO::from('2024-01-15T14:30:00+00:00');
        $earlier = DateTimeVO::from('2024-01-14T14:30:00+00:00');

        $this->assertTrue($later->isAfter($earlier));
        $this->assertFalse($earlier->isAfter($later));
    }

    public function test_is_before_returns_true_when_date_is_earlier(): void
    {
        $earlier = DateTimeVO::from('2024-01-14T14:30:00+00:00');
        $later = DateTimeVO::from('2024-01-15T14:30:00+00:00');

        $this->assertTrue($earlier->isBefore($later));
        $this->assertFalse($later->isBefore($earlier));
    }

    public function test_is_equal_returns_true_for_identical_datetimes(): void
    {
        $date1 = DateTimeVO::from('2024-01-15T14:30:00+00:00');
        $date2 = DateTimeVO::from('2024-01-15T14:30:00+00:00');

        $this->assertTrue($date1->isEqual($date2));
    }

    public function test_is_equal_returns_true_for_same_moment_in_different_timezones(): void
    {
        $paris = DateTimeVO::from('2024-01-15T14:30:00+01:00');
        $london = DateTimeVO::from('2024-01-15T13:30:00+00:00');

        $this->assertTrue($paris->isEqual($london));
    }

    public function test_is_equal_returns_false_for_different_datetimes(): void
    {
        $date1 = DateTimeVO::from('2024-01-15T14:30:00+00:00');
        $date2 = DateTimeVO::from('2024-01-16T14:30:00+00:00');

        $this->assertFalse($date1->isEqual($date2));
    }

    // ==================== STATE CHECKS TESTS ====================

    public function test_is_past_returns_true_for_past_date(): void
    {
        Carbon::setTestNow(Carbon::create(2024, 1, 15, 14, 30, 0));
        $date = DateTimeVO::from('2024-01-01T00:00:00+00:00');

        $this->assertTrue($date->isPast());
    }

    public function test_is_past_returns_false_for_future_date(): void
    {
        Carbon::setTestNow(Carbon::create(2024, 1, 15, 14, 30, 0));
        $date = DateTimeVO::from('2024-02-01T00:00:00+00:00');

        $this->assertFalse($date->isPast());
    }

    public function test_is_future_returns_true_for_future_date(): void
    {
        Carbon::setTestNow(Carbon::create(2024, 1, 15, 14, 30, 0));
        $date = DateTimeVO::from('2024-02-01T00:00:00+00:00');

        $this->assertTrue($date->isFuture());
    }

    public function test_is_future_returns_false_for_past_date(): void
    {
        Carbon::setTestNow(Carbon::create(2024, 1, 15, 14, 30, 0));
        $date = DateTimeVO::from('2024-01-01T00:00:00+00:00');

        $this->assertFalse($date->isFuture());
    }

    public function test_is_today_returns_true_for_today(): void
    {
        Carbon::setTestNow(Carbon::create(2024, 1, 15, 14, 30, 0));
        $date = DateTimeVO::from('2024-01-15T00:00:00+00:00');

        $this->assertTrue($date->isToday());
    }

    public function test_is_today_returns_false_for_yesterday(): void
    {
        Carbon::setTestNow(Carbon::create(2024, 1, 15, 14, 30, 0));
        $date = DateTimeVO::from('2024-01-14T00:00:00+00:00');

        $this->assertFalse($date->isToday());
    }

    public function test_is_tomorrow_returns_true_for_tomorrow(): void
    {
        Carbon::setTestNow(Carbon::create(2024, 1, 15, 14, 30, 0));
        $date = DateTimeVO::from('2024-01-16T00:00:00+00:00');

        $this->assertTrue($date->isTomorrow());
    }

    public function test_is_tomorrow_returns_false_for_today(): void
    {
        Carbon::setTestNow(Carbon::create(2024, 1, 15, 14, 30, 0));
        $date = DateTimeVO::from('2024-01-15T00:00:00+00:00');

        $this->assertFalse($date->isTomorrow());
    }

    public function test_is_yesterday_returns_true_for_yesterday(): void
    {
        Carbon::setTestNow(Carbon::create(2024, 1, 15, 14, 30, 0));
        $date = DateTimeVO::from('2024-01-14T00:00:00+00:00');

        $this->assertTrue($date->isYesterday());
    }

    public function test_is_yesterday_returns_false_for_today(): void
    {
        Carbon::setTestNow(Carbon::create(2024, 1, 15, 14, 30, 0));
        $date = DateTimeVO::from('2024-01-15T00:00:00+00:00');

        $this->assertFalse($date->isYesterday());
    }

    // ==================== ARITHMETIC TESTS ====================

    public function test_it_adds_days_correctly(): void
    {
        $date = DateTimeVO::from('2024-01-15T00:00:00+00:00');
        $result = $date->addDays(1);

        $this->assertSame('2024-01-16 00:00:00', $result->getValue());
        $this->assertSame('2024-01-16T00:00:00+00:00', $result->toIso8601String());
    }

    public function test_it_subtracts_days_correctly(): void
    {
        $date = DateTimeVO::from('2024-01-15T00:00:00+00:00');
        $result = $date->subDays(1);

        $this->assertSame('2024-01-14 00:00:00', $result->getValue());
        $this->assertSame('2024-01-14T00:00:00+00:00', $result->toIso8601String());
    }

    public function test_it_adds_hours_correctly(): void
    {
        $date = DateTimeVO::from('2024-01-15T00:00:00+00:00');
        $result = $date->addHours(3);

        $this->assertSame('2024-01-15 03:00:00', $result->getValue());
        $this->assertSame('2024-01-15T03:00:00+00:00', $result->toIso8601String());
    }

    public function test_it_subtracts_hours_correctly(): void
    {
        $date = DateTimeVO::from('2024-01-15T12:00:00+00:00');
        $result = $date->subHours(3);

        $this->assertSame('2024-01-15 09:00:00', $result->getValue());
        $this->assertSame('2024-01-15T09:00:00+00:00', $result->toIso8601String());
    }

    public function test_it_adds_minutes_correctly(): void
    {
        $date = DateTimeVO::from('2024-01-15T00:00:00+00:00');
        $result = $date->addMinutes(30);

        $this->assertSame('2024-01-15 00:30:00', $result->getValue());
        $this->assertSame('2024-01-15T00:30:00+00:00', $result->toIso8601String());
    }

    public function test_it_subtracts_minutes_correctly(): void
    {
        $date = DateTimeVO::from('2024-01-15T00:30:00+00:00');
        $result = $date->subMinutes(15);

        $this->assertSame('2024-01-15 00:15:00', $result->getValue());
        $this->assertSame('2024-01-15T00:15:00+00:00', $result->toIso8601String());
    }

    public function test_it_adds_months_correctly(): void
    {
        $date = DateTimeVO::from('2024-01-15T00:00:00+00:00');
        $result = $date->addMonths(1);

        $this->assertSame('2024-02-15 00:00:00', $result->getValue());
        $this->assertSame('2024-02-15T00:00:00+00:00', $result->toIso8601String());
    }

    public function test_it_subtracts_months_correctly(): void
    {
        $date = DateTimeVO::from('2024-02-15T00:00:00+00:00');
        $result = $date->subMonths(1);

        $this->assertSame('2024-01-15 00:00:00', $result->getValue());
        $this->assertSame('2024-01-15T00:00:00+00:00', $result->toIso8601String());
    }

    public function test_it_adds_years_correctly(): void
    {
        $date = DateTimeVO::from('2024-01-15T00:00:00+00:00');
        $result = $date->addYears(1);

        $this->assertSame('2025-01-15 00:00:00', $result->getValue());
        $this->assertSame('2025-01-15T00:00:00+00:00', $result->toIso8601String());
    }

    public function test_it_subtracts_years_correctly(): void
    {
        $date = DateTimeVO::from('2024-01-15T00:00:00+00:00');
        $result = $date->subYears(1);

        $this->assertSame('2023-01-15 00:00:00', $result->getValue());
        $this->assertSame('2023-01-15T00:00:00+00:00', $result->toIso8601String());
    }

    public function test_it_adds_string_interval_correctly(): void
    {
        $date = DateTimeVO::from('2024-01-15T00:00:00+00:00');
        $result = $date->add('1 week');

        $this->assertSame('2024-01-22 00:00:00', $result->getValue());
        $this->assertSame('2024-01-22T00:00:00+00:00', $result->toIso8601String());
    }

    public function test_it_subtracts_string_interval_correctly(): void
    {
        $date = DateTimeVO::from('2024-01-22T00:00:00+00:00');
        $result = $date->sub('1 week');

        $this->assertSame('2024-01-15 00:00:00', $result->getValue());
        $this->assertSame('2024-01-15T00:00:00+00:00', $result->toIso8601String());
    }

    // ==================== DIFFERENCE TESTS ====================

    public function test_it_calculates_absolute_difference_in_seconds(): void
    {
        $date1 = DateTimeVO::from('2024-01-15T00:00:00+00:00');
        $date2 = DateTimeVO::from('2024-01-15T00:01:30+00:00');

        $this->assertSame(90.0, $date1->diffInSeconds($date2));
        $this->assertSame(90.0, $date2->diffInSeconds($date1));
        $this->assertIsFloat($date1->diffInSeconds($date2));
    }

    public function test_it_calculates_absolute_difference_in_minutes(): void
    {
        $date1 = DateTimeVO::from('2024-01-15T00:00:00+00:00');
        $date2 = DateTimeVO::from('2024-01-15T00:30:00+00:00');

        $this->assertSame(30.0, $date1->diffInMinutes($date2));
        $this->assertSame(30.0, $date2->diffInMinutes($date1));
        $this->assertIsFloat($date1->diffInMinutes($date2));
    }

    public function test_it_calculates_absolute_difference_in_hours(): void
    {
        $date1 = DateTimeVO::from('2024-01-15T00:00:00+00:00');
        $date2 = DateTimeVO::from('2024-01-15T03:30:00+00:00');

        $this->assertSame(3.5, $date1->diffInHours($date2));
        $this->assertSame(3.5, $date2->diffInHours($date1));
        $this->assertIsFloat($date1->diffInHours($date2));
    }

    public function test_it_calculates_absolute_difference_in_days(): void
    {
        $date1 = DateTimeVO::from('2024-01-15T00:00:00+00:00');
        $date2 = DateTimeVO::from('2024-01-18T00:00:00+00:00');

        $this->assertSame(3.0, $date1->diffInDays($date2));
        $this->assertSame(3.0, $date2->diffInDays($date1));
        $this->assertIsFloat($date1->diffInDays($date2));
    }

    public function test_it_calculates_absolute_difference_in_months(): void
    {
        $date1 = DateTimeVO::from('2024-01-15T00:00:00+00:00');
        $date2 = DateTimeVO::from('2024-03-15T00:00:00+00:00');

        $this->assertSame(2.0, $date1->diffInMonths($date2));
        $this->assertSame(2.0, $date2->diffInMonths($date1));
        $this->assertIsFloat($date1->diffInMonths($date2));
    }

    public function test_it_calculates_absolute_difference_in_years(): void
    {
        $date1 = DateTimeVO::from('2024-01-15T00:00:00+00:00');
        $date2 = DateTimeVO::from('2026-01-15T00:00:00+00:00');

        $this->assertSame(2.0, $date1->diffInYears($date2));
        $this->assertSame(2.0, $date2->diffInYears($date1));
        $this->assertIsFloat($date1->diffInYears($date2));
    }

    // ==================== TIMEZONE TESTS ====================

    public function test_it_converts_to_utc(): void
    {
        $date = DateTimeVO::from('2024-01-15T14:30:00+01:00');
        $utcDate = $date->toUtc();

        $this->assertSame('2024-01-15 13:30:00', $utcDate->getValue());
        $this->assertSame('2024-01-15T13:30:00+00:00', $utcDate->toIso8601String());
        $this->assertContains($utcDate->getTimezone()->getName(), ['UTC', '+00:00']);
    }

    public function test_it_converts_to_specific_timezone(): void
    {
        $date = DateTimeVO::from('2024-01-15T14:30:00+00:00');
        $parisDate = $date->toTimezone('Europe/Paris');

        $this->assertSame('2024-01-15 14:30:00', $parisDate->getValue());
        $this->assertSame('2024-01-15T15:30:00+01:00', $parisDate->toIso8601String());
        $this->assertContains($parisDate->getTimezone()->getName(), ['Europe/Paris', '+01:00']);
    }

    public function test_it_returns_timezone_instance(): void
    {
        $date = DateTimeVO::from('2024-01-15T14:30:00+01:00');
        $timezone = $date->getTimezone();

        $this->assertInstanceOf(DateTimeZone::class, $timezone);
        $this->assertContains($timezone->getName(), ['+01:00', 'Europe/Paris']);
    }

    public function test_it_has_timezone_when_set(): void
    {
        $date = DateTimeVO::from('2024-01-15T14:30:00+01:00');
        $this->assertTrue($date->hasTimezone());
    }

    // ==================== GETTER TESTS ====================

    public function test_it_returns_year_component(): void
    {
        $date = DateTimeVO::from('2024-01-15T14:30:00+01:00');

        $this->assertSame(2024, $date->getYear());
        $this->assertIsInt($date->getYear());
    }

    public function test_it_returns_month_component(): void
    {
        $date = DateTimeVO::from('2024-01-15T14:30:00+01:00');

        $this->assertSame(1, $date->getMonth());
        $this->assertIsInt($date->getMonth());
    }

    public function test_it_returns_day_component(): void
    {
        $date = DateTimeVO::from('2024-01-15T14:30:00+01:00');

        $this->assertSame(15, $date->getDay());
        $this->assertIsInt($date->getDay());
    }

    public function test_it_returns_hour_component(): void
    {
        $date = DateTimeVO::from('2024-01-15T14:30:00+01:00');

        $this->assertSame(14, $date->getHour());
        $this->assertIsInt($date->getHour());
    }

    public function test_it_returns_minute_component(): void
    {
        $date = DateTimeVO::from('2024-01-15T14:30:00+01:00');

        $this->assertSame(30, $date->getMinute());
        $this->assertIsInt($date->getMinute());
    }

    public function test_it_returns_second_component(): void
    {
        $date = DateTimeVO::from('2024-01-15T14:30:45+01:00');

        $this->assertSame(45, $date->getSecond());
        $this->assertIsInt($date->getSecond());
    }

    public function test_it_returns_iso_day_of_week(): void
    {
        $date = DateTimeVO::from('2024-01-15T00:00:00+00:00');

        $this->assertSame(1, $date->getDayOfWeek());
        $this->assertIsInt($date->getDayOfWeek());
    }

    public function test_it_returns_week_of_year(): void
    {
        $date = DateTimeVO::from('2024-01-15T00:00:00+00:00');

        $this->assertSame(3, $date->getWeekOfYear());
        $this->assertIsInt($date->getWeekOfYear());
    }

    // ==================== BOUNDARY TESTS ====================

    public function test_start_of_day_returns_midnight(): void
    {
        $date = DateTimeVO::from('2024-01-15T14:30:00+00:00');
        $result = $date->startOfDay();

        $this->assertSame('2024-01-15 00:00:00', $result->getValue());
        $this->assertSame('2024-01-15T00:00:00+00:00', $result->toIso8601String());
    }

    public function test_end_of_day_returns_23_59_59(): void
    {
        $date = DateTimeVO::from('2024-01-15T14:30:00+00:00');
        $result = $date->endOfDay();

        $this->assertSame('2024-01-15 23:59:59', $result->getValue());
        $this->assertSame('2024-01-15T23:59:59+00:00', $result->toIso8601String());
    }

    public function test_start_of_month_returns_first_day_at_midnight(): void
    {
        $date = DateTimeVO::from('2024-01-15T14:30:00+00:00');
        $result = $date->startOfMonth();

        $this->assertSame('2024-01-01 00:00:00', $result->getValue());
        $this->assertSame('2024-01-01T00:00:00+00:00', $result->toIso8601String());
    }

    public function test_end_of_month_returns_last_day_at_23_59_59(): void
    {
        $date = DateTimeVO::from('2024-01-15T14:30:00+00:00');
        $result = $date->endOfMonth();

        $this->assertSame('2024-01-31 23:59:59', $result->getValue());
        $this->assertSame('2024-01-31T23:59:59+00:00', $result->toIso8601String());
    }

    public function test_start_of_year_returns_first_day_at_midnight(): void
    {
        $date = DateTimeVO::from('2024-01-15T14:30:00+00:00');
        $result = $date->startOfYear();

        $this->assertSame('2024-01-01 00:00:00', $result->getValue());
        $this->assertSame('2024-01-01T00:00:00+00:00', $result->toIso8601String());
    }

    public function test_end_of_year_returns_last_day_at_23_59_59(): void
    {
        $date = DateTimeVO::from('2024-01-15T14:30:00+00:00');
        $result = $date->endOfYear();

        $this->assertSame('2024-12-31 23:59:59', $result->getValue());
        $this->assertSame('2024-12-31T23:59:59+00:00', $result->toIso8601String());
    }

    // ==================== CARBON ACCESS TESTS ====================

    public function test_it_returns_underlying_carbon_instance(): void
    {
        $date = DateTimeVO::from('2024-01-15T14:30:00+01:00');
        $carbon = $date->getCarbon();

        $this->assertInstanceOf(CarbonInterface::class, $carbon);
        $this->assertSame('2024-01-15 14:30:00', $carbon->format('Y-m-d H:i:s'));
    }

    // ==================== IMMUTABILITY TESTS ====================

    public function test_it_creates_new_instance_when_adding_days(): void
    {
        $original = DateTimeVO::from('2024-01-15T00:00:00+00:00');
        $new = $original->addDays(1);

        $this->assertNotSame($original, $new);
        $this->assertSame('2024-01-15 00:00:00', $original->getValue());
        $this->assertSame('2024-01-16 00:00:00', $new->getValue());
    }

    public function test_it_creates_new_instance_when_subtracting_days(): void
    {
        $original = DateTimeVO::from('2024-01-15T00:00:00+00:00');
        $new = $original->subDays(1);

        $this->assertNotSame($original, $new);
        $this->assertSame('2024-01-15 00:00:00', $original->getValue());
        $this->assertSame('2024-01-14 00:00:00', $new->getValue());
    }

    public function test_it_creates_new_instance_when_adding_interval(): void
    {
        $original = DateTimeVO::from('2024-01-15T00:00:00+00:00');
        $new = $original->add('1 day');

        $this->assertNotSame($original, $new);
        $this->assertSame('2024-01-15 00:00:00', $original->getValue());
        $this->assertSame('2024-01-16 00:00:00', $new->getValue());
    }

    public function test_it_creates_new_instance_when_subtracting_interval(): void
    {
        $original = DateTimeVO::from('2024-01-15T00:00:00+00:00');
        $new = $original->sub('1 day');

        $this->assertNotSame($original, $new);
        $this->assertSame('2024-01-15 00:00:00', $original->getValue());
        $this->assertSame('2024-01-14 00:00:00', $new->getValue());
    }

    public function test_it_creates_new_instance_when_changing_timezone(): void
    {
        $original = DateTimeVO::from('2024-01-15T14:30:00+00:00');
        $new = $original->toTimezone('Europe/Paris');

        $this->assertNotSame($original, $new);
        $this->assertSame('2024-01-15 14:30:00', $original->getValue());
        $this->assertSame('2024-01-15 14:30:00', $new->getValue());
    }

    // ==================== CHAINING TESTS ====================

    public function test_it_chains_multiple_operations(): void
    {
        $result = DateTimeVO::from('2024-01-15T00:00:00+00:00')
            ->addDays(3)
            ->addHours(5)
            ->subDays(1);

        $this->assertSame('2024-01-17 05:00:00', $result->getValue());
        $this->assertSame('2024-01-17T05:00:00+00:00', $result->toIso8601String());
    }

    public function test_it_handles_complex_chaining(): void
    {
        $result = DateTimeVO::from('2024-01-15T10:30:00+00:00')
            ->addMonths(2)
            ->subDays(5)
            ->addHours(3)
            ->startOfDay();

        $this->assertSame('2024-03-10 00:00:00', $result->getValue());
        $this->assertSame('2024-03-10T00:00:00+00:00', $result->toIso8601String());
    }

    // ==================== STRING REPRESENTATION TESTS ====================

    public function test_to_string_magic_method_returns_database_format(): void
    {
        $date = DateTimeVO::from('2024-01-15T14:30:00+01:00');
        $string = (string) $date;

        $this->assertSame('2024-01-15 13:30:00', $string);
    }

    // ==================== EDGE CASE TESTS ====================

    public function test_it_preserves_timezone_in_iso_format(): void
    {
        $date = DateTimeVO::from('2024-01-15T14:30:00+01:00');

        $this->assertSame('2024-01-15 13:30:00', $date->getValue());
        $this->assertSame('2024-01-15T14:30:00+01:00', $date->toIso8601String());
    }

    public function test_it_handles_end_of_month_correctly(): void
    {
        $date = DateTimeVO::from('2024-01-31T00:00:00+00:00');
        $result = $date->addMonths(1);

        $this->assertSame('2024-03-02 00:00:00', $result->getValue());
        $this->assertSame('2024-03-02T00:00:00+00:00', $result->toIso8601String());
    }

    public function test_it_handles_leap_year_correctly(): void
    {
        $date = DateTimeVO::from('2024-02-28T00:00:00+00:00');
        $result = $date->addDays(1);

        $this->assertSame('2024-02-29 00:00:00', $result->getValue());
        $this->assertSame('2024-02-29T00:00:00+00:00', $result->toIso8601String());
    }

    public function test_it_compares_timezones_equivalently(): void
    {
        $paris = DateTimeVO::from('2024-01-15T14:30:00+01:00');
        $london = DateTimeVO::from('2024-01-15T13:30:00+00:00');

        $this->assertTrue($paris->isEqual($london));
        $this->assertFalse($paris->isAfter($london));
        $this->assertFalse($paris->isBefore($london));
    }

    public function test_it_handles_dst_transition_correctly(): void
    {
        $beforeDst = DateTimeVO::from('2024-03-31T00:00:00+00:00');
        $parisBefore = $beforeDst->toTimezone('Europe/Paris');
        $this->assertSame('2024-03-31T01:00:00+01:00', $parisBefore->toIso8601String());

        $duringDst = DateTimeVO::from('2024-03-31T01:00:00+00:00');
        $parisDuring = $duringDst->toTimezone('Europe/Paris');
        $this->assertSame('2024-03-31T03:00:00+02:00', $parisDuring->toIso8601String());

        $afterDst = DateTimeVO::from('2024-03-31T02:00:00+00:00');
        $parisAfter = $afterDst->toTimezone('Europe/Paris');
        $this->assertSame('2024-03-31T04:00:00+02:00', $parisAfter->toIso8601String());
    }

    public function test_it_creates_from_carbon_instance_in_utc(): void
    {
        $carbon = Carbon::create(2024, 1, 15, 14, 30, 0);
        $date = DateTimeVO::fromCarbon($carbon);

        $this->assertSame('2024-01-15 14:30:00', $date->getValue());
        $this->assertSame('2024-01-15T14:30:00+00:00', $date->toIso8601String());
    }

    public function test_it_creates_from_carbon_instance_with_timezone(): void
    {
        $carbon = Carbon::create(2024, 1, 15, 14, 30, 0, 'Europe/Paris');
        $date = DateTimeVO::fromCarbon($carbon);

        $this->assertSame('2024-01-15 13:30:00', $date->getValue());
        $this->assertSame('2024-01-15T14:30:00+01:00', $date->toIso8601String());
    }
}

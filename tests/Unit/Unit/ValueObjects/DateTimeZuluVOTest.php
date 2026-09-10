<?php

// tests/Unit/ValueObjects/DateTimeZuluVOTest.php

declare(strict_types=1);

namespace AndyDefer\PhpVo\Tests\Unit\ValueObjects;

use AndyDefer\PhpVo\ValueObjects\DateTimeZuluVO;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use DateTime;
use DateTimeImmutable;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class DateTimeZuluVOTest extends TestCase
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

    public function test_it_parses_iso8601_zulu_string(): void
    {
        $input = '2024-01-15T14:30:00Z';
        $date = DateTimeZuluVO::from($input);

        $this->assertSame('2024-01-15T14:30:00Z', $date->getValue());
    }

    public function test_it_parses_iso8601_with_offset_and_converts_to_utc(): void
    {
        $input = '2024-01-15T14:30:00+01:00';
        $date = DateTimeZuluVO::from($input);

        $this->assertSame('2024-01-15T13:30:00Z', $date->getValue());
    }

    public function test_it_parses_database_datetime_string_as_utc(): void
    {
        $input = '2024-01-15 14:30:00';
        $date = DateTimeZuluVO::from($input);

        $this->assertSame('2024-01-15T14:30:00Z', $date->getValue());
    }

    public function test_it_parses_date_only_string_as_utc_midnight(): void
    {
        $input = '2024-01-15';
        $date = DateTimeZuluVO::from($input);

        $this->assertSame('2024-01-15T00:00:00Z', $date->getValue());
    }

    public function test_it_returns_current_utc_datetime_when_null_provided(): void
    {
        Carbon::setTestNow(Carbon::create(2024, 1, 15, 14, 30, 0, 'UTC'));
        $date = DateTimeZuluVO::from(null);

        $this->assertSame('2024-01-15T14:30:00Z', $date->getValue());
    }

    public function test_it_throws_exception_for_invalid_string(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid datetime value');
        DateTimeZuluVO::from('invalid-date');
    }

    public function test_it_returns_same_instance_when_created_from_existing(): void
    {
        $original = DateTimeZuluVO::from('2024-01-15T14:30:00Z');
        $duplicate = DateTimeZuluVO::from($original);

        $this->assertSame($original, $duplicate);
    }

    // ==================== FACTORY METHODS TESTS ====================

    public function test_now_returns_current_utc_datetime(): void
    {
        Carbon::setTestNow(Carbon::create(2024, 1, 15, 14, 30, 0, 'UTC'));
        $now = DateTimeZuluVO::now();

        $this->assertSame('2024-01-15T14:30:00Z', $now->getValue());
    }

    public function test_today_returns_midnight_of_current_day_utc(): void
    {
        Carbon::setTestNow(Carbon::create(2024, 1, 15, 14, 30, 0, 'UTC'));
        $today = DateTimeZuluVO::today();

        $this->assertSame('2024-01-15T00:00:00Z', $today->getValue());
    }

    public function test_create_builds_datetime_from_parts_in_utc(): void
    {
        $date = DateTimeZuluVO::create(2024, 1, 15, 14, 30, 0);

        $this->assertSame('2024-01-15T14:30:00Z', $date->getValue());
    }

    public function test_create_with_default_time(): void
    {
        $date = DateTimeZuluVO::create(2024, 1, 15);

        $this->assertSame('2024-01-15T00:00:00Z', $date->getValue());
    }

    public function test_from_carbon_converts_to_utc(): void
    {
        $carbon = Carbon::create(2024, 1, 15, 14, 30, 0, 'Europe/Paris');
        $date = DateTimeZuluVO::fromCarbon($carbon);

        $this->assertSame('2024-01-15T13:30:00Z', $date->getValue());
    }

    public function test_from_carbon_with_utc_instance(): void
    {
        $carbon = Carbon::create(2024, 1, 15, 14, 30, 0, 'UTC');
        $date = DateTimeZuluVO::fromCarbon($carbon);

        $this->assertSame('2024-01-15T14:30:00Z', $date->getValue());
    }

    // ==================== VALUE RETRIEVAL TESTS ====================

    public function test_get_value_returns_iso8601_zulu_format(): void
    {
        $date = DateTimeZuluVO::from('2024-01-15T14:30:00Z');
        $value = $date->getValue();

        $this->assertSame('2024-01-15T14:30:00Z', $value);
        $this->assertIsString($value);
    }

    public function test_to_string_magic_method_returns_zulu_format(): void
    {
        $date = DateTimeZuluVO::from('2024-01-15T14:30:00Z');
        $string = (string) $date;

        $this->assertSame('2024-01-15T14:30:00Z', $string);
    }

    public function test_get_carbon_returns_carbon_instance(): void
    {
        $date = DateTimeZuluVO::from('2024-01-15T14:30:00Z');
        $carbon = $date->getCarbon();

        $this->assertInstanceOf(CarbonInterface::class, $carbon);
        $this->assertSame('UTC', $carbon->getTimezone()->getName());
    }

    // ==================== CONVERSION TESTS ====================

    public function test_it_converts_to_database_string(): void
    {
        $date = DateTimeZuluVO::from('2024-01-15T14:30:00Z');
        $result = $date->toDateTimeString();

        $this->assertSame('2024-01-15 14:30:00', $result);
    }

    public function test_it_converts_to_date_string(): void
    {
        $date = DateTimeZuluVO::from('2024-01-15T14:30:00Z');
        $result = $date->toDateString();

        $this->assertSame('2024-01-15', $result);
    }

    public function test_it_converts_to_time_string(): void
    {
        $date = DateTimeZuluVO::from('2024-01-15T14:30:00Z');
        $result = $date->toTimeString();

        $this->assertSame('14:30:00', $result);
    }

    public function test_it_converts_to_unix_timestamp(): void
    {
        $date = DateTimeZuluVO::from('2024-01-15T14:30:00Z');
        $expected = 1705329000;

        $result = $date->toTimestamp();

        $this->assertSame($expected, $result);
        $this->assertIsInt($result);
    }

    public function test_it_converts_to_native_datetime_instance(): void
    {
        $date = DateTimeZuluVO::from('2024-01-15T14:30:00Z');
        $dateTime = $date->toDateTime();

        $this->assertInstanceOf(DateTime::class, $dateTime);
        $this->assertSame('2024-01-15 14:30:00', $dateTime->format('Y-m-d H:i:s'));
        $this->assertSame('UTC', $dateTime->getTimezone()->getName());
    }

    public function test_it_converts_to_native_datetime_immutable_instance(): void
    {
        $date = DateTimeZuluVO::from('2024-01-15T14:30:00Z');
        $dateTime = $date->toDateTimeImmutable();

        $this->assertInstanceOf(DateTimeImmutable::class, $dateTime);
        $this->assertSame('2024-01-15 14:30:00', $dateTime->format('Y-m-d H:i:s'));
        $this->assertSame('UTC', $dateTime->getTimezone()->getName());
    }

    // ==================== FORMATTING TESTS ====================

    public function test_it_formats_with_custom_format(): void
    {
        $date = DateTimeZuluVO::from('2024-01-15T14:30:00Z');

        $this->assertSame('15/01/2024', $date->format('d/m/Y'));
        $this->assertSame('14:30', $date->format('H:i'));
        $this->assertSame('January', $date->format('F'));
        $this->assertSame('2024', $date->format('Y'));
    }

    // ==================== COMPARISON TESTS ====================

    public function test_is_after_returns_true_when_date_is_later(): void
    {
        $later = DateTimeZuluVO::from('2024-01-15T14:30:00Z');
        $earlier = DateTimeZuluVO::from('2024-01-14T14:30:00Z');

        $this->assertTrue($later->isAfter($earlier));
        $this->assertFalse($earlier->isAfter($later));
    }

    public function test_is_after_or_equal_returns_true_when_date_is_same_or_later(): void
    {
        $date1 = DateTimeZuluVO::from('2024-01-15T14:30:00Z');
        $date2 = DateTimeZuluVO::from('2024-01-15T14:30:00Z');
        $date3 = DateTimeZuluVO::from('2024-01-14T14:30:00Z');

        $this->assertTrue($date1->isAfterOrEqual($date2));
        $this->assertTrue($date1->isAfterOrEqual($date3));
    }

    public function test_is_before_returns_true_when_date_is_earlier(): void
    {
        $earlier = DateTimeZuluVO::from('2024-01-14T14:30:00Z');
        $later = DateTimeZuluVO::from('2024-01-15T14:30:00Z');

        $this->assertTrue($earlier->isBefore($later));
        $this->assertFalse($later->isBefore($earlier));
    }

    public function test_is_before_or_equal_returns_true_when_date_is_same_or_earlier(): void
    {
        $date1 = DateTimeZuluVO::from('2024-01-15T14:30:00Z');
        $date2 = DateTimeZuluVO::from('2024-01-15T14:30:00Z');
        $date3 = DateTimeZuluVO::from('2024-01-16T14:30:00Z');

        $this->assertTrue($date1->isBeforeOrEqual($date2));
        $this->assertTrue($date1->isBeforeOrEqual($date3));
    }

    public function test_is_equal_returns_true_for_identical_datetimes(): void
    {
        $date1 = DateTimeZuluVO::from('2024-01-15T14:30:00Z');
        $date2 = DateTimeZuluVO::from('2024-01-15T14:30:00Z');

        $this->assertTrue($date1->isEqual($date2));
    }

    public function test_is_equal_returns_true_for_same_moment_in_different_timezones(): void
    {
        $paris = DateTimeZuluVO::from('2024-01-15T14:30:00+01:00');
        $london = DateTimeZuluVO::from('2024-01-15T13:30:00Z');

        $this->assertTrue($paris->isEqual($london));
    }

    public function test_is_equal_returns_false_for_different_datetimes(): void
    {
        $date1 = DateTimeZuluVO::from('2024-01-15T14:30:00Z');
        $date2 = DateTimeZuluVO::from('2024-01-16T14:30:00Z');

        $this->assertFalse($date1->isEqual($date2));
    }

    public function test_is_between_returns_true_when_inside_range(): void
    {
        $date = DateTimeZuluVO::from('2024-01-15T12:00:00Z');
        $start = DateTimeZuluVO::from('2024-01-15T10:00:00Z');
        $end = DateTimeZuluVO::from('2024-01-15T14:00:00Z');

        $this->assertTrue($date->isBetween($start, $end));
    }

    public function test_is_between_returns_false_when_outside_range(): void
    {
        $date = DateTimeZuluVO::from('2024-01-15T16:00:00Z');
        $start = DateTimeZuluVO::from('2024-01-15T10:00:00Z');
        $end = DateTimeZuluVO::from('2024-01-15T14:00:00Z');

        $this->assertFalse($date->isBetween($start, $end));
    }

    public function test_is_between_with_boundaries_included_by_default(): void
    {
        $start = DateTimeZuluVO::from('2024-01-15T10:00:00Z');
        $end = DateTimeZuluVO::from('2024-01-15T14:00:00Z');

        $this->assertTrue($start->isBetween($start, $end));
        $this->assertTrue($end->isBetween($start, $end));
    }

    public function test_is_between_excludes_boundaries_when_specified(): void
    {
        $start = DateTimeZuluVO::from('2024-01-15T10:00:00Z');
        $end = DateTimeZuluVO::from('2024-01-15T14:00:00Z');

        $this->assertFalse($start->isBetween($start, $end, includeStart: false));
        $this->assertFalse($end->isBetween($start, $end, includeEnd: false));
    }

    public function test_is_between_returns_false_for_invalid_range(): void
    {
        $date = DateTimeZuluVO::from('2024-01-15T12:00:00Z');
        $start = DateTimeZuluVO::from('2024-01-15T14:00:00Z');
        $end = DateTimeZuluVO::from('2024-01-15T10:00:00Z');

        $this->assertFalse($date->isBetween($start, $end));
    }

    public function test_is_same_day_returns_true_for_same_date(): void
    {
        $date1 = DateTimeZuluVO::from('2024-01-15T10:00:00Z');
        $date2 = DateTimeZuluVO::from('2024-01-15T20:00:00Z');

        $this->assertTrue($date1->isSameDay($date2));
    }

    public function test_is_same_day_returns_false_for_different_dates(): void
    {
        $date1 = DateTimeZuluVO::from('2024-01-15T10:00:00Z');
        $date2 = DateTimeZuluVO::from('2024-01-16T10:00:00Z');

        $this->assertFalse($date1->isSameDay($date2));
    }

    public function test_is_cross_day_returns_true_when_different_days(): void
    {
        $date1 = DateTimeZuluVO::from('2024-01-16T10:00:00Z');
        $date2 = DateTimeZuluVO::from('2024-01-15T10:00:00Z');

        $this->assertTrue($date1->isCrossDay($date2));
    }

    public function test_is_cross_day_returns_false_for_same_day(): void
    {
        $date1 = DateTimeZuluVO::from('2024-01-15T20:00:00Z');
        $date2 = DateTimeZuluVO::from('2024-01-15T10:00:00Z');

        $this->assertFalse($date1->isCrossDay($date2));
    }

    public function test_is_same_hour_returns_true_for_same_hour(): void
    {
        $date1 = DateTimeZuluVO::from('2024-01-15T14:00:00Z');
        $date2 = DateTimeZuluVO::from('2024-01-15T14:59:59Z');

        $this->assertTrue($date1->isSameHour($date2));
    }

    public function test_is_same_hour_returns_false_for_different_hour(): void
    {
        $date1 = DateTimeZuluVO::from('2024-01-15T14:00:00Z');
        $date2 = DateTimeZuluVO::from('2024-01-15T15:00:00Z');

        $this->assertFalse($date1->isSameHour($date2));
    }

    // ==================== STATE CHECKS TESTS ====================

    public function test_is_past_returns_true_for_past_date(): void
    {
        Carbon::setTestNow(Carbon::create(2024, 1, 15, 14, 30, 0, 'UTC'));
        $date = DateTimeZuluVO::from('2024-01-01T00:00:00Z');

        $this->assertTrue($date->isPast());
    }

    public function test_is_past_returns_false_for_future_date(): void
    {
        Carbon::setTestNow(Carbon::create(2024, 1, 15, 14, 30, 0, 'UTC'));
        $date = DateTimeZuluVO::from('2024-02-01T00:00:00Z');

        $this->assertFalse($date->isPast());
    }

    public function test_is_future_returns_true_for_future_date(): void
    {
        Carbon::setTestNow(Carbon::create(2024, 1, 15, 14, 30, 0, 'UTC'));
        $date = DateTimeZuluVO::from('2024-02-01T00:00:00Z');

        $this->assertTrue($date->isFuture());
    }

    public function test_is_future_returns_false_for_past_date(): void
    {
        Carbon::setTestNow(Carbon::create(2024, 1, 15, 14, 30, 0, 'UTC'));
        $date = DateTimeZuluVO::from('2024-01-01T00:00:00Z');

        $this->assertFalse($date->isFuture());
    }

    public function test_is_today_returns_true_for_today(): void
    {
        Carbon::setTestNow(Carbon::create(2024, 1, 15, 14, 30, 0, 'UTC'));
        $date = DateTimeZuluVO::from('2024-01-15T00:00:00Z');

        $this->assertTrue($date->isToday());
    }

    public function test_is_today_returns_false_for_yesterday(): void
    {
        Carbon::setTestNow(Carbon::create(2024, 1, 15, 14, 30, 0, 'UTC'));
        $date = DateTimeZuluVO::from('2024-01-14T00:00:00Z');

        $this->assertFalse($date->isToday());
    }

    public function test_is_tomorrow_returns_true_for_tomorrow(): void
    {
        Carbon::setTestNow(Carbon::create(2024, 1, 15, 14, 30, 0, 'UTC'));
        $date = DateTimeZuluVO::from('2024-01-16T00:00:00Z');

        $this->assertTrue($date->isTomorrow());
    }

    public function test_is_tomorrow_returns_false_for_today(): void
    {
        Carbon::setTestNow(Carbon::create(2024, 1, 15, 14, 30, 0, 'UTC'));
        $date = DateTimeZuluVO::from('2024-01-15T00:00:00Z');

        $this->assertFalse($date->isTomorrow());
    }

    public function test_is_yesterday_returns_true_for_yesterday(): void
    {
        Carbon::setTestNow(Carbon::create(2024, 1, 15, 14, 30, 0, 'UTC'));
        $date = DateTimeZuluVO::from('2024-01-14T00:00:00Z');

        $this->assertTrue($date->isYesterday());
    }

    public function test_is_yesterday_returns_false_for_today(): void
    {
        Carbon::setTestNow(Carbon::create(2024, 1, 15, 14, 30, 0, 'UTC'));
        $date = DateTimeZuluVO::from('2024-01-15T00:00:00Z');

        $this->assertFalse($date->isYesterday());
    }

    // ==================== ARITHMETIC TESTS ====================

    public function test_it_adds_days_correctly(): void
    {
        $date = DateTimeZuluVO::from('2024-01-15T00:00:00Z');
        $result = $date->addDays(1);

        $this->assertSame('2024-01-16T00:00:00Z', $result->getValue());
    }

    public function test_it_subtracts_days_correctly(): void
    {
        $date = DateTimeZuluVO::from('2024-01-15T00:00:00Z');
        $result = $date->subDays(1);

        $this->assertSame('2024-01-14T00:00:00Z', $result->getValue());
    }

    public function test_it_adds_hours_correctly(): void
    {
        $date = DateTimeZuluVO::from('2024-01-15T00:00:00Z');
        $result = $date->addHours(3);

        $this->assertSame('2024-01-15T03:00:00Z', $result->getValue());
    }

    public function test_it_subtracts_hours_correctly(): void
    {
        $date = DateTimeZuluVO::from('2024-01-15T12:00:00Z');
        $result = $date->subHours(3);

        $this->assertSame('2024-01-15T09:00:00Z', $result->getValue());
    }

    public function test_it_adds_minutes_correctly(): void
    {
        $date = DateTimeZuluVO::from('2024-01-15T00:00:00Z');
        $result = $date->addMinutes(30);

        $this->assertSame('2024-01-15T00:30:00Z', $result->getValue());
    }

    public function test_it_subtracts_minutes_correctly(): void
    {
        $date = DateTimeZuluVO::from('2024-01-15T00:30:00Z');
        $result = $date->subMinutes(15);

        $this->assertSame('2024-01-15T00:15:00Z', $result->getValue());
    }

    public function test_it_adds_months_correctly(): void
    {
        $date = DateTimeZuluVO::from('2024-01-15T00:00:00Z');
        $result = $date->addMonths(1);

        $this->assertSame('2024-02-15T00:00:00Z', $result->getValue());
    }

    public function test_it_subtracts_months_correctly(): void
    {
        $date = DateTimeZuluVO::from('2024-02-15T00:00:00Z');
        $result = $date->subMonths(1);

        $this->assertSame('2024-01-15T00:00:00Z', $result->getValue());
    }

    public function test_it_adds_years_correctly(): void
    {
        $date = DateTimeZuluVO::from('2024-01-15T00:00:00Z');
        $result = $date->addYears(1);

        $this->assertSame('2025-01-15T00:00:00Z', $result->getValue());
    }

    public function test_it_subtracts_years_correctly(): void
    {
        $date = DateTimeZuluVO::from('2024-01-15T00:00:00Z');
        $result = $date->subYears(1);

        $this->assertSame('2023-01-15T00:00:00Z', $result->getValue());
    }

    // ==================== DIFFERENCE TESTS ====================

    public function test_it_calculates_absolute_difference_in_seconds(): void
    {
        $date1 = DateTimeZuluVO::from('2024-01-15T00:00:00Z');
        $date2 = DateTimeZuluVO::from('2024-01-15T00:01:30Z');

        $this->assertSame(90.0, $date1->diffInSeconds($date2));
        $this->assertSame(90.0, $date2->diffInSeconds($date1));
        $this->assertIsFloat($date1->diffInSeconds($date2));
    }

    public function test_it_calculates_absolute_difference_in_minutes(): void
    {
        $date1 = DateTimeZuluVO::from('2024-01-15T00:00:00Z');
        $date2 = DateTimeZuluVO::from('2024-01-15T00:30:00Z');

        $this->assertSame(30.0, $date1->diffInMinutes($date2));
        $this->assertSame(30.0, $date2->diffInMinutes($date1));
        $this->assertIsFloat($date1->diffInMinutes($date2));
    }

    public function test_it_calculates_absolute_difference_in_hours(): void
    {
        $date1 = DateTimeZuluVO::from('2024-01-15T00:00:00Z');
        $date2 = DateTimeZuluVO::from('2024-01-15T03:30:00Z');

        $this->assertSame(3.5, $date1->diffInHours($date2));
        $this->assertSame(3.5, $date2->diffInHours($date1));
        $this->assertIsFloat($date1->diffInHours($date2));
    }

    public function test_it_calculates_absolute_difference_in_days(): void
    {
        $date1 = DateTimeZuluVO::from('2024-01-15T00:00:00Z');
        $date2 = DateTimeZuluVO::from('2024-01-18T00:00:00Z');

        $this->assertSame(3.0, $date1->diffInDays($date2));
        $this->assertSame(3.0, $date2->diffInDays($date1));
        $this->assertIsFloat($date1->diffInDays($date2));
    }

    public function test_it_calculates_absolute_difference_in_months(): void
    {
        $date1 = DateTimeZuluVO::from('2024-01-15T00:00:00Z');
        $date2 = DateTimeZuluVO::from('2024-03-15T00:00:00Z');

        $this->assertSame(2.0, $date1->diffInMonths($date2));
        $this->assertSame(2.0, $date2->diffInMonths($date1));
        $this->assertIsFloat($date1->diffInMonths($date2));
    }

    public function test_it_calculates_absolute_difference_in_years(): void
    {
        $date1 = DateTimeZuluVO::from('2024-01-15T00:00:00Z');
        $date2 = DateTimeZuluVO::from('2026-01-15T00:00:00Z');

        $this->assertSame(2.0, $date1->diffInYears($date2));
        $this->assertSame(2.0, $date2->diffInYears($date1));
        $this->assertIsFloat($date1->diffInYears($date2));
    }

    // ==================== GETTER TESTS ====================

    public function test_it_returns_year_component(): void
    {
        $date = DateTimeZuluVO::from('2024-01-15T14:30:00Z');

        $this->assertSame(2024, $date->getYear());
        $this->assertIsInt($date->getYear());
    }

    public function test_it_returns_month_component(): void
    {
        $date = DateTimeZuluVO::from('2024-01-15T14:30:00Z');

        $this->assertSame(1, $date->getMonth());
        $this->assertIsInt($date->getMonth());
    }

    public function test_it_returns_day_component(): void
    {
        $date = DateTimeZuluVO::from('2024-01-15T14:30:00Z');

        $this->assertSame(15, $date->getDay());
        $this->assertIsInt($date->getDay());
    }

    public function test_it_returns_hour_component(): void
    {
        $date = DateTimeZuluVO::from('2024-01-15T14:30:00Z');

        $this->assertSame(14, $date->getHour());
        $this->assertIsInt($date->getHour());
    }

    public function test_it_returns_minute_component(): void
    {
        $date = DateTimeZuluVO::from('2024-01-15T14:30:00Z');

        $this->assertSame(30, $date->getMinute());
        $this->assertIsInt($date->getMinute());
    }

    public function test_it_returns_second_component(): void
    {
        $date = DateTimeZuluVO::from('2024-01-15T14:30:45Z');

        $this->assertSame(45, $date->getSecond());
        $this->assertIsInt($date->getSecond());
    }

    public function test_it_returns_iso_day_of_week(): void
    {
        $date = DateTimeZuluVO::from('2024-01-15T00:00:00Z');

        $this->assertSame(1, $date->getDayOfWeek());
        $this->assertIsInt($date->getDayOfWeek());
    }

    public function test_it_returns_week_of_year(): void
    {
        $date = DateTimeZuluVO::from('2024-01-15T00:00:00Z');

        $this->assertSame(3, $date->getWeekOfYear());
        $this->assertIsInt($date->getWeekOfYear());
    }

    // ==================== BOUNDARY TESTS ====================

    public function test_start_of_day_returns_midnight(): void
    {
        $date = DateTimeZuluVO::from('2024-01-15T14:30:00Z');
        $result = $date->startOfDay();

        $this->assertSame('2024-01-15T00:00:00Z', $result->getValue());
    }

    public function test_end_of_day_returns_23_59_59(): void
    {
        $date = DateTimeZuluVO::from('2024-01-15T14:30:00Z');
        $result = $date->endOfDay();

        $this->assertSame('2024-01-15T23:59:59Z', $result->getValue());
    }

    public function test_start_of_month_returns_first_day_at_midnight(): void
    {
        $date = DateTimeZuluVO::from('2024-01-15T14:30:00Z');
        $result = $date->startOfMonth();

        $this->assertSame('2024-01-01T00:00:00Z', $result->getValue());
    }

    public function test_end_of_month_returns_last_day_at_23_59_59(): void
    {
        $date = DateTimeZuluVO::from('2024-01-15T14:30:00Z');
        $result = $date->endOfMonth();

        $this->assertSame('2024-01-31T23:59:59Z', $result->getValue());
    }

    public function test_start_of_year_returns_first_day_at_midnight(): void
    {
        $date = DateTimeZuluVO::from('2024-06-15T14:30:00Z');
        $result = $date->startOfYear();

        $this->assertSame('2024-01-01T00:00:00Z', $result->getValue());
    }

    public function test_end_of_year_returns_last_day_at_23_59_59(): void
    {
        $date = DateTimeZuluVO::from('2024-06-15T14:30:00Z');
        $result = $date->endOfYear();

        $this->assertSame('2024-12-31T23:59:59Z', $result->getValue());
    }

    // ==================== IMMUTABILITY TESTS ====================

    public function test_it_creates_new_instance_when_adding_days(): void
    {
        $original = DateTimeZuluVO::from('2024-01-15T00:00:00Z');
        $new = $original->addDays(1);

        $this->assertNotSame($original, $new);
        $this->assertSame('2024-01-15T00:00:00Z', $original->getValue());
        $this->assertSame('2024-01-16T00:00:00Z', $new->getValue());
    }

    public function test_it_creates_new_instance_when_subtracting_days(): void
    {
        $original = DateTimeZuluVO::from('2024-01-15T00:00:00Z');
        $new = $original->subDays(1);

        $this->assertNotSame($original, $new);
        $this->assertSame('2024-01-15T00:00:00Z', $original->getValue());
        $this->assertSame('2024-01-14T00:00:00Z', $new->getValue());
    }

    public function test_it_creates_new_instance_when_starting_day(): void
    {
        $original = DateTimeZuluVO::from('2024-01-15T14:30:00Z');
        $new = $original->startOfDay();

        $this->assertNotSame($original, $new);
        $this->assertSame('2024-01-15T14:30:00Z', $original->getValue());
        $this->assertSame('2024-01-15T00:00:00Z', $new->getValue());
    }

    // ==================== CHAINING TESTS ====================

    public function test_it_chains_multiple_operations(): void
    {
        $result = DateTimeZuluVO::from('2024-01-15T00:00:00Z')
            ->addDays(3)
            ->addHours(5)
            ->subDays(1);

        $this->assertSame('2024-01-17T05:00:00Z', $result->getValue());
    }

    public function test_it_handles_complex_chaining(): void
    {
        $result = DateTimeZuluVO::from('2024-01-15T10:30:00Z')
            ->addMonths(2)
            ->subDays(5)
            ->addHours(3)
            ->startOfDay();

        $this->assertSame('2024-03-10T00:00:00Z', $result->getValue());
    }

    // ==================== EDGE CASE TESTS ====================

    public function test_it_always_returns_utc_timezone(): void
    {
        $date = DateTimeZuluVO::from('2024-01-15T14:30:00+01:00');

        $this->assertSame('UTC', $date->getCarbon()->getTimezone()->getName());
        $this->assertSame('2024-01-15T13:30:00Z', $date->getValue());
    }

    public function test_it_handles_end_of_month_correctly(): void
    {
        $date = DateTimeZuluVO::from('2024-01-31T00:00:00Z');
        $result = $date->addMonths(1);

        // Carbon gère le débordement de mois en passant au mois suivant
        $this->assertSame('2024-03-02T00:00:00Z', $result->getValue());
    }

    public function test_it_handles_leap_year_correctly(): void
    {
        $date = DateTimeZuluVO::from('2024-02-28T00:00:00Z');
        $result = $date->addDays(1);

        $this->assertSame('2024-02-29T00:00:00Z', $result->getValue());
    }

    public function test_it_preserves_utc_consistency_across_operations(): void
    {
        $date = DateTimeZuluVO::from('2024-01-15T14:30:00+05:00');
        $result = $date
            ->addDays(1)
            ->addHours(2)
            ->subMonths(1);

        $this->assertSame('2023-12-16T11:30:00Z', $result->getValue());
        $this->assertSame('UTC', $result->getCarbon()->getTimezone()->getName());
    }

    public function test_it_converts_different_timezone_inputs_to_same_utc(): void
    {
        $paris = DateTimeZuluVO::from('2024-01-15T14:30:00+01:00');
        $london = DateTimeZuluVO::from('2024-01-15T13:30:00Z');
        $newYork = DateTimeZuluVO::from('2024-01-15T08:30:00-05:00');

        $this->assertTrue($paris->isEqual($london));
        $this->assertTrue($paris->isEqual($newYork));
        $this->assertTrue($london->isEqual($newYork));

        $this->assertSame('2024-01-15T13:30:00Z', $paris->getValue());
        $this->assertSame('2024-01-15T13:30:00Z', $london->getValue());
        $this->assertSame('2024-01-15T13:30:00Z', $newYork->getValue());
    }
}

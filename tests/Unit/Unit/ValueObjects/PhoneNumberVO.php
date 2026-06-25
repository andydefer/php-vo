<?php

declare(strict_types=1);

namespace AndyDefer\PhpVo\Tests\Unit\ValueObjects;

use AndyDefer\PhpVo\Enums\CallingCode;
use AndyDefer\PhpVo\Records\PhoneNumberRecord;
use AndyDefer\PhpVo\ValueObjects\PhoneNumberVO;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class PhoneNumberVOTest extends TestCase
{
    // ==================== CREATE FROM ARRAY TESTS ====================

    public function test_create_valid_french_phone_number(): void
    {
        $phone = PhoneNumberVO::from([
            'callingCode' => 'FR',
            'number' => '612345678',
        ]);

        $this->assertSame(CallingCode::FR, $phone->getCallingCode());
        $this->assertSame('612345678', $phone->getNumber());
    }

    public function test_create_valid_us_phone_number(): void
    {
        $phone = PhoneNumberVO::from([
            'callingCode' => 'US',
            'number' => '2025550123',
        ]);

        $this->assertSame(CallingCode::US, $phone->getCallingCode());
        $this->assertSame('2025550123', $phone->getNumber());
    }

    public function test_create_valid_uk_phone_number(): void
    {
        $phone = PhoneNumberVO::from([
            'callingCode' => 'GB',
            'number' => '7911123456',
        ]);

        $this->assertSame(CallingCode::GB, $phone->getCallingCode());
        $this->assertSame('7911123456', $phone->getNumber());
    }

    public function test_create_from_calling_code_enum_directly(): void
    {
        $phone = new PhoneNumberVO(CallingCode::FR, '612345678');

        $this->assertSame(CallingCode::FR, $phone->getCallingCode());
        $this->assertSame('612345678', $phone->getNumber());
    }

    // ==================== VALIDATION TESTS ====================

    public function test_create_with_letters_in_number_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Phone number must contain only digits');

        PhoneNumberVO::from(['callingCode' => 'FR', 'number' => '6123ABCD78']);
    }

    public function test_create_with_empty_number_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Phone number cannot be empty');

        PhoneNumberVO::from(['callingCode' => 'FR', 'number' => '']);
    }

    public function test_create_with_special_characters_in_number_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Phone number must contain only digits');

        PhoneNumberVO::from(['callingCode' => 'FR', 'number' => '61-23-45-67-8']);
    }

    public function test_create_with_total_digits_exceeding_15_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('exceeds maximum allowed');

        PhoneNumberVO::from(['callingCode' => 'FR', 'number' => '61234567890123']);
    }

    public function test_create_with_maximum_allowed_digits_works(): void
    {
        $phone = PhoneNumberVO::from(['callingCode' => 'FR', 'number' => '6123456789012']);

        $this->assertSame('6123456789012', $phone->getNumber());
        $this->assertSame(15, $phone->getTotalDigits());
    }

    // ==================== GETTER TESTS ====================

    public function test_get_calling_code_returns_enum(): void
    {
        $phone = PhoneNumberVO::from(['callingCode' => 'CD', 'number' => '812345678']);

        $this->assertSame(CallingCode::CD, $phone->getCallingCode());
        $this->assertInstanceOf(CallingCode::class, $phone->getCallingCode());
    }

    public function test_get_number_returns_string(): void
    {
        $phone = PhoneNumberVO::from(['callingCode' => 'FR', 'number' => '612345678']);

        $this->assertSame('612345678', $phone->getNumber());
        $this->assertIsString($phone->getNumber());
    }

    // ==================== FORMAT METHOD TESTS ====================

    public function test_to_e164_returns_compact_format(): void
    {
        $phone = PhoneNumberVO::from(['callingCode' => 'FR', 'number' => '612345678']);

        $this->assertSame('+33612345678', $phone->toE164());
    }

    // ==================== FORMAT HUMAN TESTS ====================

    public function test_format_human_auto_for_french_phone_uses_groups_of_2(): void
    {
        $phone = PhoneNumberVO::from(['callingCode' => 'FR', 'number' => '612345678']);

        // FR calling code has 2 digits → auto groups of 2
        $this->assertSame('+33 6 12 34 56 78', $phone->formatHuman());
    }

    public function test_format_human_auto_for_us_phone_uses_groups_of_2(): void
    {
        $phone = PhoneNumberVO::from(['callingCode' => 'US', 'number' => '2025550123']);

        // US calling code has 1 digit → default groups of 2
        $this->assertSame('+1 20 25 55 01 23', $phone->formatHuman());
    }

    public function test_format_human_auto_for_french_guiana_uses_groups_of_3(): void
    {
        $phone = PhoneNumberVO::from(['callingCode' => 'GF', 'number' => '123456789']);

        // GF calling code has 3 digits → auto groups of 3
        $this->assertSame('+594 123 456 789', $phone->formatHuman());
    }

    public function test_format_human_with_manual_group_size_2(): void
    {
        $phone = PhoneNumberVO::from(['callingCode' => 'GF', 'number' => '123456789']);

        // Force groups of 2 even though calling code has 3 digits
        $this->assertSame('+594 12 34 56 78 9', $phone->formatHuman(2));
    }

    public function test_format_human_with_manual_group_size_3(): void
    {
        $phone = PhoneNumberVO::from(['callingCode' => 'FR', 'number' => '612345678']);

        // Force groups of 3 even though calling code has 2 digits
        $this->assertSame('+33 612 345 678', $phone->formatHuman(3));
    }

    public function test_format_human_with_odd_number_of_digits_auto(): void
    {
        $phone = PhoneNumberVO::from(['callingCode' => 'FR', 'number' => '61234567']);

        // Last group has 1 digit
        $this->assertSame('+33 6 12 34 56 7', $phone->formatHuman());
    }

    public function test_format_human_with_odd_number_of_digits_manual_group_3(): void
    {
        $phone = PhoneNumberVO::from(['callingCode' => 'FR', 'number' => '61234567']);

        // Groups of 3, last group has 1 digit
        $this->assertSame('+33 61 234 567', $phone->formatHuman(3));
    }

    public function test_format_human_throws_exception_for_invalid_group_size(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Group size must be 2, 3, or null (auto), got: 4');

        $phone = PhoneNumberVO::from(['callingCode' => 'FR', 'number' => '612345678']);
        $phone->formatHuman(4);
    }

    // ==================== FORMAT HUMAN FOR DIFFERENT COUNTRIES ====================

    public function test_format_human_for_long_calling_code_auto(): void
    {
        $phone = PhoneNumberVO::from(['callingCode' => 'RE', 'number' => '123456789']);

        // RE (Réunion) has 3 digits → auto groups of 3
        $this->assertSame('+262 123 456 789', $phone->formatHuman());
    }

    public function test_format_human_for_one_digit_calling_code_auto(): void
    {
        $phone = PhoneNumberVO::from(['callingCode' => 'CA', 'number' => '2025550123']);

        // CA has 1 digit → default groups of 2
        $this->assertSame('+1 20 25 55 01 23', $phone->formatHuman());
    }

    public function test_format_human_for_congo_phone(): void
    {
        $phone = PhoneNumberVO::from(['callingCode' => 'CD', 'number' => '812345678']);

        // CD has 3 digits → auto groups of 3
        $this->assertSame('+243 812 345 678', $phone->formatHuman());
    }

    // ==================== GET_TOTAL_DIGITS TESTS ====================

    public function test_get_total_digits_returns_correct_count(): void
    {
        $phone = PhoneNumberVO::from(['callingCode' => 'FR', 'number' => '612345678']);

        $this->assertSame(11, $phone->getTotalDigits());
    }

    // ==================== GET_VALUE RECORD TESTS ====================

    public function test_get_value_returns_phone_number_record(): void
    {
        $phone = PhoneNumberVO::from(['callingCode' => 'FR', 'number' => '612345678']);
        $record = $phone->getValue();

        $this->assertInstanceOf(PhoneNumberRecord::class, $record);
        $this->assertSame(CallingCode::FR, $record->callingCode);
        $this->assertSame('612345678', $record->number);
    }

    // ==================== EXISTING INSTANCE TESTS ====================

    public function test_create_from_existing_phone_returns_same_instance(): void
    {
        $original = PhoneNumberVO::from(['callingCode' => 'FR', 'number' => '612345678']);
        $duplicate = PhoneNumberVO::from($original);

        $this->assertSame($original, $duplicate);
    }

    // ==================== EQUALITY TESTS ====================

    public function test_equals_returns_true_for_same_phone_number(): void
    {
        $phone1 = PhoneNumberVO::from(['callingCode' => 'FR', 'number' => '612345678']);
        $phone2 = PhoneNumberVO::from(['callingCode' => 'FR', 'number' => '612345678']);

        $this->assertTrue($phone1->equals($phone2));
    }

    public function test_equals_returns_false_for_different_calling_code(): void
    {
        $phone1 = PhoneNumberVO::from(['callingCode' => 'FR', 'number' => '612345678']);
        $phone2 = PhoneNumberVO::from(['callingCode' => 'BE', 'number' => '612345678']);

        $this->assertFalse($phone1->equals($phone2));
    }

    public function test_equals_returns_false_for_different_number(): void
    {
        $phone1 = PhoneNumberVO::from(['callingCode' => 'FR', 'number' => '612345678']);
        $phone2 = PhoneNumberVO::from(['callingCode' => 'FR', 'number' => '698765432']);

        $this->assertFalse($phone1->equals($phone2));
    }

    // ==================== STRING REPRESENTATION TESTS ====================

    public function test_to_string_magic_method_returns_auto_human_format(): void
    {
        $phone = PhoneNumberVO::from(['callingCode' => 'FR', 'number' => '612345678']);

        $this->assertSame('+33 6 12 34 56 78', (string) $phone);
    }

    // ==================== EDGE CASES TESTS ====================

    public function test_handles_long_calling_codes_correctly(): void
    {
        $phone = PhoneNumberVO::from(['callingCode' => 'GF', 'number' => '123456789']);

        $this->assertSame('+594 123 456 789', $phone->formatHuman());
        $this->assertSame('+594123456789', $phone->toE164());
    }

    public function test_handles_one_digit_calling_code_correctly(): void
    {
        $phone = PhoneNumberVO::from(['callingCode' => 'US', 'number' => '2025550123']);

        $this->assertSame('+1 20 25 55 01 23', $phone->formatHuman());
    }

    public function test_handles_calling_code_by_alpha2_key(): void
    {
        $phone = PhoneNumberVO::from(['callingCode' => 'CD', 'number' => '812345678']);

        $this->assertSame(CallingCode::CD, $phone->getCallingCode());
        $this->assertSame('+243 812 345 678', $phone->formatHuman());
    }

    public function test_handles_very_long_number_with_auto_detection(): void
    {
        $phone = PhoneNumberVO::from(['callingCode' => 'FR', 'number' => '6123456789012']);

        // 13 digits number (max allowed)
        $this->assertSame('+33 6 12 34 56 78 90 12', $phone->formatHuman());
    }

    public function test_handles_very_long_number_with_manual_group_3(): void
    {
        $phone = PhoneNumberVO::from(['callingCode' => 'FR', 'number' => '6123456789012']);

        $this->assertSame('+33 612 345 678 901 2', $phone->formatHuman(3));
    }
}

<?php

declare(strict_types=1);

namespace AndyDefer\PhpVo\ValueObjects;

use AndyDefer\DomainStructures\Abstracts\AbstractValueObject;
use AndyDefer\PhpVo\Enums\CallingCode;
use AndyDefer\PhpVo\Records\PhoneNumberRecord;
use InvalidArgumentException;

/**
 * Value Object representing a validated international phone number.
 *
 * Validates that the total length (calling code + number) does not exceed 15 digits
 * (ITU-T E.164 standard). Provides formatting capabilities for display.
 *
 * @example
 * $phone = PhoneNumberVO::from(['callingCode' => 'FR', 'number' => '612345678']);
 * echo $phone->toE164();        // '+33612345678'
 * echo $phone->formatHuman();   // '+33 6 12 34 56 78' (auto: groups of 2)
 * echo $phone->formatHuman(3);  // '+33 612 345 678' (groups of 3)
 * echo $phone->getValue();      // PhoneNumberRecord
 */
final class PhoneNumberVO extends AbstractValueObject
{
    private const MAX_TOTAL_LENGTH = 15;

    public function __construct(
        private readonly CallingCode $callingCode,
        private readonly string $number
    ) {
        $this->validate($callingCode, $number);
    }

    /**
     * Validate the phone number.
     *
     * @param CallingCode $callingCode The calling code enum
     * @param string $number The subscriber number (digits only)
     *
     * @throws InvalidArgumentException If validation fails
     */
    private function validate(CallingCode $callingCode, string $number): void
    {
        // Check that number contains only digits
        if (!preg_match('/^\d+$/', $number)) {
            throw new InvalidArgumentException(
                sprintf('Phone number must contain only digits, got: %s', $number)
            );
        }

        // Check that number is not empty
        if (empty($number)) {
            throw new InvalidArgumentException('Phone number cannot be empty');
        }

        // Check total length (calling code digits + number digits) <= 15
        $callingCodeDigits = strlen($callingCode->value);
        $numberDigits = strlen($number);
        $totalDigits = $callingCodeDigits + $numberDigits;

        if ($totalDigits > self::MAX_TOTAL_LENGTH) {
            throw new InvalidArgumentException(
                sprintf(
                    'Total phone number length (%d digits) exceeds maximum allowed (%d digits)',
                    $totalDigits,
                    self::MAX_TOTAL_LENGTH
                )
            );
        }
    }

    /**
     * Returns the phone number as a data record.
     *
     * @return PhoneNumberRecord The phone number converted to a Record structure
     */
    public function getValue(): PhoneNumberRecord
    {
        return new PhoneNumberRecord(
            callingCode: $this->callingCode,
            number: $this->number
        );
    }

    /**
     * Get the calling code enum.
     *
     * @return CallingCode The calling code
     */
    public function getCallingCode(): CallingCode
    {
        return $this->callingCode;
    }

    /**
     * Get the subscriber number (digits only).
     *
     * @return string The subscriber number
     */
    public function getNumber(): string
    {
        return $this->number;
    }

    /**
     * Format the phone number in E.164 standard format.
     * Example: +33612345678
     *
     * @return string E.164 format (no spaces, plus sign prefix)
     */
    public function toE164(): string
    {
        return '+' . $this->callingCode->value . $this->number;
    }

    /**
     * Format the phone number in human-readable format.
     * Groups digits by the specified size (2 or 3).
     *
     * Auto-detection rules:
     * - If $groupSize is null: auto-detect based on calling code length
     *   - Calling code has 2 digits (e.g., FR, BE) → groups of 2
     *   - Calling code has 3 digits (e.g., GF, RE) → groups of 3
     * - If $groupSize is provided: use specified value (2 or 3)
     *
     * @param int|null $groupSize Number of digits per group (2 or 3), null for auto-detection
     *
     * @return string Human-readable format
     *
     * @throws InvalidArgumentException If groupSize is not 2, 3, or null
     */
    public function formatHuman(?int $groupSize = null): string
    {
        // Validate group size
        if ($groupSize !== null && !in_array($groupSize, [2, 3], true)) {
            throw new InvalidArgumentException(
                sprintf('Group size must be 2, 3, or null (auto), got: %d', $groupSize)
            );
        }

        // Auto-detect group size based on calling code length
        if ($groupSize === null) {
            $callingCodeLength = strlen($this->callingCode->value);
            $groupSize = $callingCodeLength === 2 ? 2 : 3;
        }

        // Split number into groups of specified size
        $groups = str_split($this->number, $groupSize);
        $formattedNumber = implode(' ', $groups);

        return '+' . $this->callingCode->value . ' ' . $formattedNumber;
    }

    /**
     * Get the total number of digits (calling code + number).
     *
     * @return int Total digits count
     */
    public function getTotalDigits(): int
    {
        return strlen($this->callingCode->value) + strlen($this->number);
    }

    /**
     * Returns the human-formatted phone number as string (auto-detection).
     *
     * @return string The human-formatted phone number
     */
    public function __toString(): string
    {
        return $this->formatHuman();
    }
}

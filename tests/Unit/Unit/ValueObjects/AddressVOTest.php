<?php

declare(strict_types=1);

namespace AndyDefer\PhpVo\Tests\Unit\ValueObjects;

use AndyDefer\PhpVo\Records\AddressRecord;
use AndyDefer\PhpVo\ValueObjects\AddressVO;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class AddressVOTest extends TestCase
{
    private const VALID_ADDRESS_DATA = [
        'street' => '123 Main St',
        'city' => 'Paris',
        'postalCode' => '75001',
        'country' => 'France'
    ];

    public function test_create_address_with_valid_data(): void
    {
        // Arrange: Create address from valid data array
        $address = AddressVO::from(self::VALID_ADDRESS_DATA);

        // Assert: Verify all properties are correctly set
        $this->assertSame('123 Main St', $address->street);
        $this->assertSame('Paris', $address->city);
        $this->assertSame('75001', $address->postalCode->getValue());
        $this->assertSame('France', $address->country);
    }

    public function test_create_address_with_empty_street_throws_exception(): void
    {
        // Arrange: Prepare data with empty street
        $data = [
            'street' => '',
            'city' => 'Paris',
            'postalCode' => '75001',
            'country' => 'France'
        ];

        // Assert: Exception is thrown for empty street
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Street address cannot be empty');

        // Act: Attempt to create address
        AddressVO::from($data);
    }

    public function test_create_address_with_empty_city_throws_exception(): void
    {
        // Arrange: Prepare data with empty city
        $data = [
            'street' => '123 Main St',
            'city' => '',
            'postalCode' => '75001',
            'country' => 'France'
        ];

        // Assert: Exception is thrown for empty city
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('City cannot be empty');

        // Act: Attempt to create address
        AddressVO::from($data);
    }

    public function test_create_address_with_empty_country_throws_exception(): void
    {
        // Arrange: Prepare data with empty country
        $data = [
            'street' => '123 Main St',
            'city' => 'Paris',
            'postalCode' => '75001',
            'country' => ''
        ];

        // Assert: Exception is thrown for empty country
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Country cannot be empty');

        // Act: Attempt to create address
        AddressVO::from($data);
    }

    public function test_create_address_from_array_with_snake_case(): void
    {
        // Arrange: Create address with snake_case keys (postal_code instead of postalCode)
        $address = AddressVO::from([
            'street' => '456 Oak Ave',
            'city' => 'Lyon',
            'postal_code' => '69001',
            'country' => 'France'
        ]);

        // Assert: Keys are normalized automatically
        $this->assertSame('456 Oak Ave', $address->street);
        $this->assertSame('Lyon', $address->city);
        $this->assertSame('69001', $address->postalCode->getValue());
        $this->assertSame('France', $address->country);
    }

    public function test_create_address_from_json_string(): void
    {
        // Arrange: Create address from JSON string
        $json = '{"street":"789 Pine St","city":"Marseille","postal_code":"13001","country":"France"}';
        $address = AddressVO::fromJson($json);

        // Assert: JSON is properly decoded and properties are set
        $this->assertSame('789 Pine St', $address->street);
        $this->assertSame('Marseille', $address->city);
        $this->assertSame('13001', $address->postalCode->getValue());
        $this->assertSame('France', $address->country);
    }

    public function test_create_address_from_existing_address_returns_same_instance(): void
    {
        // Arrange: Create original address and duplicate it
        $original = AddressVO::from(self::VALID_ADDRESS_DATA);
        $duplicate = AddressVO::from($original);

        // Assert: Same instance is returned (identity preservation)
        $this->assertSame($original, $duplicate);
    }

    public function test_format_returns_formatted_address(): void
    {
        // Arrange: Create an address
        $address = AddressVO::from(self::VALID_ADDRESS_DATA);

        // Act & Assert: Format should be single-line with comma separators
        $this->assertSame('123 Main St, 75001 Paris, France', $address->format());
    }

    public function test_format_shipping_label(): void
    {
        // Arrange: Create an address
        $address = AddressVO::from(self::VALID_ADDRESS_DATA);

        // Act: Generate shipping label format
        $expected = "123 Main St\n75001 Paris\nFrance";

        // Assert: Format uses line breaks
        $this->assertSame($expected, $address->formatShippingLabel());
    }

    public function test_is_in_country(): void
    {
        // Arrange: Create a French address
        $address = AddressVO::from(self::VALID_ADDRESS_DATA);

        // Assert: Country check is case-insensitive
        $this->assertTrue($address->isInCountry('France'));
        $this->assertTrue($address->isInCountry('FRANCE'));
        $this->assertFalse($address->isInCountry('Germany'));
    }

    public function test_get_postal_code_prefix(): void
    {
        // Arrange: Create address with specific postal code
        $address = AddressVO::from(self::VALID_ADDRESS_DATA);

        // Assert: Returns first two characters of postal code
        $this->assertSame('75', $address->getPostalCodePrefix());
    }

    public function test_get_value_returns_address_record(): void
    {
        // Arrange: Create address
        $address = AddressVO::from(self::VALID_ADDRESS_DATA);

        // Act: Convert to Record
        $record = $address->getValue();

        // Assert: Returns proper Record instance with same data
        $this->assertInstanceOf(AddressRecord::class, $record);
        $this->assertSame('123 Main St', $record->street);
        $this->assertSame('Paris', $record->city);
        $this->assertSame('75001', $record->postalCode->getValue());
        $this->assertSame('France', $record->country);
    }

    public function test_equals_method(): void
    {
        // Arrange: Create three addresses (two identical, one different)
        $address1 = AddressVO::from(self::VALID_ADDRESS_DATA);
        $address2 = AddressVO::from(self::VALID_ADDRESS_DATA);
        $address3 = AddressVO::from([
            'street' => '456 Oak Ave',
            'city' => 'Lyon',
            'postalCode' => '69001',
            'country' => 'France'
        ]);

        // Assert: Same data = equal, different data = not equal
        $this->assertTrue($address1->equals($address2));
        $this->assertFalse($address1->equals($address3));
    }

    public function test_to_string_returns_formatted_address(): void
    {
        // Arrange: Create address
        $address = AddressVO::from(self::VALID_ADDRESS_DATA);

        // Assert: String casting uses format() method
        $this->assertSame('123 Main St, 75001 Paris, France', (string)$address);
    }
}

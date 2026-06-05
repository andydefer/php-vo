<?php

declare(strict_types=1);

namespace AndyDefer\PhpVo\Tests\Unit\ValueObjects;

use AndyDefer\PhpVo\Records\AddressRecord;
use AndyDefer\PhpVo\ValueObjects\Address;
use AndyDefer\PhpVo\ValueObjects\AddressVO;
use PHPUnit\Framework\TestCase;

final class AddressVOTest extends TestCase
{
    public function test_create_address_with_valid_data(): void
    {
        $address = AddressVO::from([
            'street' => '123 Main St',
            'city' => 'Paris',
            'postalCode' => '75001',
            'country' => 'France'
        ]);

        $this->assertSame('123 Main St', $address->street);
        $this->assertSame('Paris', $address->city);
        $this->assertSame('75001', $address->postalCode->getValue());
        $this->assertSame('France', $address->country);
    }

    public function test_create_address_from_array_with_snake_case(): void
    {
        $address = AddressVO::from([
            'street' => '456 Oak Ave',
            'city' => 'Lyon',
            'postal_code' => '69001',
            'country' => 'France'
        ]);

        $this->assertSame('456 Oak Ave', $address->street);
        $this->assertSame('Lyon', $address->city);
        $this->assertSame('69001', $address->postalCode->getValue());
        $this->assertSame('France', $address->country);
    }

    public function test_create_address_from_json_string(): void
    {
        $json = '{"street":"789 Pine St","city":"Marseille","postal_code":"13001","country":"France"}';
        $address = AddressVO::fromJson($json);

        $this->assertSame('789 Pine St', $address->street);
        $this->assertSame('Marseille', $address->city);
        $this->assertSame('13001', $address->postalCode->getValue());
        $this->assertSame('France', $address->country);
    }

    public function test_create_address_from_existing_address_returns_same_instance(): void
    {
        $original = AddressVO::from([
            'street' => '123 Main St',
            'city' => 'Paris',
            'postalCode' => '75001',
            'country' => 'France'
        ]);

        $duplicate = AddressVO::from($original);

        $this->assertSame($original, $duplicate);
    }

    public function test_format_returns_formatted_address(): void
    {
        $address = AddressVO::from([
            'street' => '123 Main St',
            'city' => 'Paris',
            'postalCode' => '75001',
            'country' => 'France'
        ]);

        $this->assertSame('123 Main St, 75001 Paris, France', $address->format());
    }

    public function test_format_shipping_label(): void
    {
        $address = AddressVO::from([
            'street' => '123 Main St',
            'city' => 'Paris',
            'postalCode' => '75001',
            'country' => 'France'
        ]);

        $expected = "123 Main St\n75001 Paris\nFrance";
        $this->assertSame($expected, $address->formatShippingLabel());
    }

    public function test_is_in_country(): void
    {
        $address = AddressVO::from([
            'street' => '123 Main St',
            'city' => 'Paris',
            'postalCode' => '75001',
            'country' => 'France'
        ]);

        $this->assertTrue($address->isInCountry('France'));
        $this->assertTrue($address->isInCountry('FRANCE'));
        $this->assertFalse($address->isInCountry('Germany'));
    }

    public function test_get_postal_code_prefix(): void
    {
        $address = AddressVO::from([
            'street' => '123 Main St',
            'city' => 'Paris',
            'postalCode' => '75001',
            'country' => 'France'
        ]);

        $this->assertSame('75', $address->getPostalCodePrefix());
    }

    public function test_get_value_returns_address_record(): void
    {
        $address = AddressVO::from([
            'street' => '123 Main St',
            'city' => 'Paris',
            'postalCode' => '75001',
            'country' => 'France'
        ]);

        $record = $address->getValue();

        $this->assertInstanceOf(AddressRecord::class, $record);
        $this->assertSame('123 Main St', $record->street);
        $this->assertSame('Paris', $record->city);
        $this->assertSame('75001', $record->postalCode->getValue());
        $this->assertSame('France', $record->country);
    }

    public function test_equals_method(): void
    {
        $address1 = AddressVO::from([
            'street' => '123 Main St',
            'city' => 'Paris',
            'postalCode' => '75001',
            'country' => 'France'
        ]);

        $address2 = AddressVO::from([
            'street' => '123 Main St',
            'city' => 'Paris',
            'postalCode' => '75001',
            'country' => 'France'
        ]);

        $address3 = AddressVO::from([
            'street' => '456 Oak Ave',
            'city' => 'Lyon',
            'postalCode' => '69001',
            'country' => 'France'
        ]);

        $this->assertTrue($address1->equals($address2));
        $this->assertFalse($address1->equals($address3));
    }

    public function test_to_string_returns_formatted_address(): void
    {
        $address = AddressVO::from([
            'street' => '123 Main St',
            'city' => 'Paris',
            'postalCode' => '75001',
            'country' => 'France'
        ]);

        $this->assertSame('123 Main St, 75001 Paris, France', (string)$address);
    }
}

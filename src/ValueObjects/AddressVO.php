<?php

declare(strict_types=1);

namespace AndyDefer\PhpVo\ValueObjects;

use AndyDefer\DomainStructures\Abstracts\AbstractValueObject;
use AndyDefer\PhpVo\Records\AddressRecord;
use InvalidArgumentException;

/**
 * Immutable value object representing a physical address.
 *
 * Combines street, city, postal code, and country into a single coherent
 * structure. Provides formatting capabilities for different use cases
 * (display, shipping labels).
 *
 * @example
 * $address = AddressVO::from([
 *     'street' => '123 Main St',
 *     'city' => 'Paris',
 *     'postalCode' => '75001',
 *     'country' => 'France'
 * ]);
 * echo $address->format(); // "123 Main St, 75001 Paris, France"
 */
class AddressVO extends AbstractValueObject
{
    public function __construct(
        private readonly string $street,
        private readonly string $city,
        private readonly PostalCodeVO $postalCode,
        private readonly string $country,
        private readonly ?CoordinatesVO $coordinates = null,
    ) {
        $this->validate($street, $city, $postalCode, $country);
    }

    /**
     * Returns the address as a data record.
     *
     * @return AddressRecord The address converted to a Record structure
     */
    public function getValue(): AddressRecord
    {
        return new AddressRecord(
            street: $this->street,
            city: $this->city,
            postalCode: $this->postalCode,
            country: $this->country,
            coordinates: $this->coordinates?->getValue(),
        );
    }

    /**
     * Ensures the address contains all required fields with valid values.
     *
     * @param  string  $street  The street address
     * @param  string  $city  The city name
     * @param  PostalCodeVO  $postalCode  The postal code
     * @param  string  $country  The country name
     *
     * @throws InvalidArgumentException If any required field is empty
     */
    private function validate(string $street, string $city, PostalCodeVO $postalCode, string $country): void
    {
        if (empty($street)) {
            throw new InvalidArgumentException('Street address cannot be empty');
        }

        if (empty($city)) {
            throw new InvalidArgumentException('City cannot be empty');
        }

        if (empty($country)) {
            throw new InvalidArgumentException('Country cannot be empty');
        }

        // PostalCodeVO already validates itself internally
        // No need to check if postal code is empty as it's a VO
    }

    /**
     * Formats the address as a single-line string.
     *
     * @return string The formatted address (e.g., "123 Main St, 75001 Paris, France")
     */
    public function format(): string
    {
        return sprintf(
            '%s, %s %s, %s',
            $this->street,
            $this->postalCode->getValue(),
            $this->city,
            $this->country
        );
    }

    /**
     * Formats the address for shipping labels (multi-line).
     *
     * @return string The address formatted with line breaks
     */
    public function formatShippingLabel(): string
    {
        return implode("\n", [
            $this->street,
            sprintf('%s %s', $this->postalCode->getValue(), $this->city),
            $this->country,
        ]);
    }

    /**
     * Checks if the address is located in a specific country.
     *
     * @param  string  $country  The country name to check against (case-insensitive)
     * @return bool True if the address is in the specified country
     */
    public function isInCountry(string $country): bool
    {
        return strtolower($this->country) === strtolower($country);
    }

    /**
     * Gets the first two digits of the postal code.
     *
     * Useful for department/region grouping in countries like France.
     *
     * @return string The postal code prefix (first 2 characters)
     */
    public function getPostalCodePrefix(): string
    {
        return substr($this->postalCode->getValue(), 0, 2);
    }

    /**
     * Returns the coordinates if present.
     *
     * @return CoordinatesVO|null The coordinates or null if not set
     */
    public function getCoordinates(): ?CoordinatesVO
    {
        return $this->coordinates;
    }

    /**
     * Checks if the address has coordinates.
     *
     * @return bool True if coordinates are set
     */
    public function hasCoordinates(): bool
    {
        return $this->coordinates !== null;
    }

    /**
     * Returns the formatted address as a string.
     *
     * @return string The formatted address
     */
    public function __toString(): string
    {
        return $this->format();
    }
}

<?php

declare(strict_types=1);

namespace AndyDefer\PhpVo\ValueObjects;

use AndyDefer\DomainStructures\Abstracts\AbstractValueObject;
use AndyDefer\DomainStructures\Traits\HasPropertiesAccess;
use AndyDefer\PhpVo\Records\AddressRecord;
use InvalidArgumentException;

final class Address extends AbstractValueObject
{
    public function __construct(
        private readonly string $street,
        private readonly string $city,
        private readonly PostalCode $postalCode,
        private readonly string $country
    ) {
        $this->validate($street, $city, $postalCode, $country);
    }

    public function getValue(): AddressRecord
    {
        return new AddressRecord(
            street: $this->street,
            city: $this->city,
            postalCode: $this->postalCode,
            country: $this->country
        );
    }

    private function validate(string $street, string $city, PostalCode $postalCode, string $country): void
    {
        if (empty($street) && empty($city) && empty($postalCode) && empty($country)) {
            throw new InvalidArgumentException('Address cannot be completely empty');
        }
    }

    public function format(): string
    {
        return $this->street . ', ' . $this->postalCode->getValue() . ' ' . $this->city . ', ' . $this->country;
    }

    public function formatShippingLabel(): string
    {
        return implode("\n", [
            $this->street,
            "{$this->postalCode->getValue()} {$this->city}",
            $this->country,
        ]);
    }

    public function isInCountry(string $country): bool
    {
        return strtolower($this->country) === strtolower($country);
    }

    public function getPostalCodePrefix(): string
    {
        return substr($this->postalCode->getValue(), 0, 2);
    }

    public function __toString(): string
    {
        return $this->format();
    }
}

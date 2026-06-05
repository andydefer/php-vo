<?php

declare(strict_types=1);

namespace AndyDefer\PhpVo\ValueObjects;

use AndyDefer\DomainStructures\Abstracts\AbstractValueObject;
use InvalidArgumentException;

/**
 * Email Value Object.
 * 
 * Represents a validated email address.
 * 
 * @example
 * $email = EmailVO::from('user@example.com');
 * echo $email->getValue(); // 'user@example.com'
 * echo $email->getDomain(); // 'example'
 * echo $email->getTld(); // 'com'
 * echo $email->getUsername(); // 'user'
 */
final class EmailVO extends AbstractValueObject
{
    public function __construct(protected readonly string $value) {}

    public static function from(mixed $source): static
    {
        if ($source instanceof self) {
            return $source;
        }

        if (!is_string($source)) {
            throw new InvalidArgumentException('Email must be a string');
        }

        $value = trim($source);

        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException(sprintf(
                'Invalid email format: "%s".',
                $value
            ));
        }

        return new self($value);
    }

    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Get the domain name without TLD.
     *
     * @return string The domain (e.g., 'example', 'orange')
     */
    public function getDomain(): string
    {
        $fullDomain = substr(strrchr($this->value, '@'), 1);
        $parts = explode('.', $fullDomain);

        return $parts[0];
    }

    /**
     * Get the TLD (Top Level Domain).
     *
     * @return string The TLD (e.g., 'com', 'fr', 'net')
     */
    public function getTld(): string
    {
        $fullDomain = substr(strrchr($this->value, '@'), 1);
        $parts = explode('.', $fullDomain);

        return end($parts);
    }

    /**
     * Get the full domain with TLD.
     *
     * @return string The full domain (e.g., 'example.com', 'orange.fr')
     */
    public function getFullDomain(): string
    {
        return substr(strrchr($this->value, '@'), 1);
    }

    /**
     * Get the username/local part of the email.
     *
     * @return string The username
     */
    public function getUsername(): string
    {
        return explode('@', $this->value)[0];
    }
}

<?php

declare(strict_types=1);

namespace AndyDefer\PhpVo\Tests\Unit\ValueObjects;

use AndyDefer\PhpVo\ValueObjects\Email;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class EmailTest extends TestCase
{
    public function test_create_valid_email(): void
    {
        $email = Email::from('user@example.com');

        $this->assertSame('user@example.com', $email->getValue());
    }

    public function test_create_email_with_spaces_is_trimmed(): void
    {
        $email = Email::from(' user@example.com ');

        $this->assertSame('user@example.com', $email->getValue());
    }

    public function test_create_email_without_at_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid email format: "userexample.com".');

        Email::from('userexample.com');
    }

    public function test_create_email_with_invalid_format_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid email format: "user@".');

        Email::from('user@');
    }

    public function test_create_email_from_integer_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Email must be a string');

        Email::from(12345);
    }

    public function test_create_email_from_array_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Email must be a string');

        Email::from(['user@example.com']);
    }

    public function test_create_email_from_null_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Email must be a string');

        Email::from(null);
    }

    public function test_create_email_from_existing_email_returns_same_instance(): void
    {
        $original = Email::from('user@example.com');
        $duplicate = Email::from($original);

        $this->assertSame($original, $duplicate);
    }

    public function test_get_domain_returns_domain_without_tld(): void
    {
        $email = Email::from('user@example.com');
        $this->assertSame('example', $email->getDomain());

        $email2 = Email::from('user@orange.fr');
        $this->assertSame('orange', $email2->getDomain());

        $email3 = Email::from('user@subdomain.example.co.uk');
        $this->assertSame('subdomain', $email3->getDomain());
    }

    public function test_get_tld_returns_top_level_domain(): void
    {
        $email = Email::from('user@example.com');
        $this->assertSame('com', $email->getTld());

        $email2 = Email::from('user@orange.fr');
        $this->assertSame('fr', $email2->getTld());

        $email3 = Email::from('user@example.co.uk');
        $this->assertSame('uk', $email3->getTld());
    }

    public function test_get_full_domain_returns_domain_with_tld(): void
    {
        $email = Email::from('user@example.com');
        $this->assertSame('example.com', $email->getFullDomain());

        $email2 = Email::from('user@orange.fr');
        $this->assertSame('orange.fr', $email2->getFullDomain());

        $email3 = Email::from('user@subdomain.example.co.uk');
        $this->assertSame('subdomain.example.co.uk', $email3->getFullDomain());
    }

    public function test_get_username_returns_local_part(): void
    {
        $email = Email::from('user@example.com');
        $this->assertSame('user', $email->getUsername());

        $email2 = Email::from('john.doe@example.com');
        $this->assertSame('john.doe', $email2->getUsername());

        $email3 = Email::from('john+filter@example.com');
        $this->assertSame('john+filter', $email3->getUsername());
    }
}

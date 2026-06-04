<?php

declare(strict_types=1);

namespace AndyDefer\PhpVo\Tests\Unit\ValueObjects;

use AndyDefer\PhpVo\Enums\Currency;
use AndyDefer\PhpVo\Records\MoneyRecord;
use AndyDefer\PhpVo\ValueObjects\Amount;
use AndyDefer\PhpVo\ValueObjects\Money;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class MoneyTest extends TestCase
{
    public function test_create_money_with_valid_amount_and_currency(): void
    {
        $amount = Amount::from(99.99);
        $money = new Money($amount, Currency::EUR);

        $this->assertSame(99.99, $money->amount->getValue());
        $this->assertSame(Currency::EUR, $money->currency);
    }

    public function test_create_money_with_zero_amount_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Amount must be positive');

        $amount = Amount::from(0);
        new Money($amount, Currency::EUR);
    }

    public function test_create_money_with_negative_amount_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Amount must be positive');

        $amount = Amount::from(-50);
        new Money($amount, Currency::EUR);
    }

    public function test_get_value_returns_money_record(): void
    {
        $amount = Amount::from(99.99);
        $money = new Money($amount, Currency::EUR);
        $record = $money->getValue();

        $this->assertInstanceOf(MoneyRecord::class, $record);
        $this->assertSame(99.99, $record->amount->getValue());
        $this->assertSame(Currency::EUR, $record->currency);
    }

    public function test_add_vat(): void
    {
        $amount = Amount::from(100);
        $money = new Money($amount, Currency::EUR);
        $withVat = $money->addVat(20);

        $this->assertSame(120.0, $withVat->amount->getValue());
        $this->assertSame(Currency::EUR, $withVat->currency);
    }

    public function test_remove_vat(): void
    {
        $amount = Amount::from(120);
        $money = new Money($amount, Currency::EUR);
        $withoutVat = $money->removeVat(20);

        $this->assertSame(100.0, $withoutVat->amount->getValue());
        $this->assertSame(Currency::EUR, $withoutVat->currency);
    }

    public function test_get_vat_amount(): void
    {
        $amount = Amount::from(100);
        $money = new Money($amount, Currency::EUR);
        $vatAmount = $money->getVatAmount(20);

        $this->assertSame(20.0, $vatAmount->amount->getValue());
        $this->assertSame(Currency::EUR, $vatAmount->currency);
    }

    public function test_plus(): void
    {
        $amount1 = Amount::from(50);
        $amount2 = Amount::from(30);
        $money1 = new Money($amount1, Currency::EUR);
        $money2 = new Money($amount2, Currency::EUR);
        $total = $money1->plus($money2);

        $this->assertSame(80.0, $total->amount->getValue());
        $this->assertSame(Currency::EUR, $total->currency);
    }

    public function test_plus_different_currencies_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot operate on different currencies: EUR and USD');

        $amount1 = Amount::from(50);
        $amount2 = Amount::from(30);
        $money1 = new Money($amount1, Currency::EUR);
        $money2 = new Money($amount2, Currency::USD);
        $money1->plus($money2);
    }

    public function test_minus(): void
    {
        $amount1 = Amount::from(50);
        $amount2 = Amount::from(20);
        $money1 = new Money($amount1, Currency::EUR);
        $money2 = new Money($amount2, Currency::EUR);
        $diff = $money1->minus($money2);

        $this->assertSame(30.0, $diff->amount->getValue());
        $this->assertSame(Currency::EUR, $diff->currency);
    }

    public function test_minus_different_currencies_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot operate on different currencies');

        $amount1 = Amount::from(50);
        $amount2 = Amount::from(20);
        $money1 = new Money($amount1, Currency::EUR);
        $money2 = new Money($amount2, Currency::USD);
        $money1->minus($money2);
    }

    public function test_multiply(): void
    {
        $amount = Amount::from(10);
        $money = new Money($amount, Currency::EUR);
        $multiplied = $money->multiply(2.5);

        $this->assertSame(25.0, $multiplied->amount->getValue());
        $this->assertSame(Currency::EUR, $multiplied->currency);
    }

    public function test_divide(): void
    {
        $amount = Amount::from(100);
        $money = new Money($amount, Currency::EUR);
        $divided = $money->divide(4);

        $this->assertSame(25.0, $divided->amount->getValue());
        $this->assertSame(Currency::EUR, $divided->currency);
    }

    public function test_divide_by_zero_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Division by zero');

        $amount = Amount::from(100);
        $money = new Money($amount, Currency::EUR);
        $money->divide(0);
    }

    public function test_percentage(): void
    {
        $amount = Amount::from(200);
        $money = new Money($amount, Currency::EUR);
        $percentage = $money->percentage(15);

        $this->assertSame(30.0, $percentage->amount->getValue());
        $this->assertSame(Currency::EUR, $percentage->currency);
    }

    public function test_format(): void
    {
        $amount = Amount::from(99.99);
        $money = new Money($amount, Currency::EUR);
        $this->assertSame('99.99 €', $money->format());

        $money2 = new Money($amount, Currency::USD);
        $this->assertSame('99.99 $', $money2->format());
    }

    public function test_format_with_iso(): void
    {
        $amount = Amount::from(99.99);
        $money = new Money($amount, Currency::EUR);
        $this->assertSame('99.99 EUR', $money->formatWithIso());

        $money2 = new Money($amount, Currency::USD);
        $this->assertSame('99.99 USD', $money2->formatWithIso());
    }

    public function test_to_string(): void
    {
        $amount = Amount::from(99.99);
        $money = new Money($amount, Currency::EUR);
        $this->assertSame('99.99 €', (string) $money);
    }

    public function test_immutability(): void
    {
        $amount = Amount::from(100);
        $original = new Money($amount, Currency::EUR);
        $new = $original->plus(new Money(Amount::from(50), Currency::EUR));

        $this->assertNotSame($original, $new);
        $this->assertSame(100.0, $original->amount->getValue());
        $this->assertSame(150.0, $new->amount->getValue());
    }

    public function test_property_access_via_has_properties_access(): void
    {
        $amount = Amount::from(99.99);
        $money = new Money($amount, Currency::EUR);

        $this->assertSame(99.99, $money->amount->getValue());
        $this->assertSame(Currency::EUR, $money->currency);
    }
}

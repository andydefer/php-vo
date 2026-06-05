<?php

declare(strict_types=1);

namespace AndyDefer\PhpVo\ValueObjects;

use AndyDefer\DomainStructures\Abstracts\AbstractValueObject;
use AndyDefer\DomainStructures\Traits\HasPropertiesAccess;
use AndyDefer\PhpVo\Enums\Currency;
use AndyDefer\PhpVo\Records\MoneyRecord;
use InvalidArgumentException;

final class MoneyVO extends AbstractValueObject
{

    public function __construct(
        private readonly AmountVO $amount,
        private readonly Currency $currency
    ) {
        if ($amount->getValue() <= 0) {
            throw new InvalidArgumentException('Amount must be positive');
        }
    }

    public function getValue(): MoneyRecord
    {
        return new MoneyRecord($this->amount, $this->currency);
    }

    public function addVat(float|int|string $rate): self
    {
        $vatAmount = $this->amount->percentage($rate);
        $newAmount = $this->amount->add($vatAmount);
        return new self($newAmount, $this->currency);
    }

    public function removeVat(float|int|string $rate): self
    {
        $divisor = 1 + ((float)$rate / 100);
        $newAmount = $this->amount->divide($divisor);
        return new self($newAmount, $this->currency);
    }

    public function getVatAmount(float|int|string $rate): self
    {
        $vatAmount = $this->amount->percentage($rate);
        return new self($vatAmount, $this->currency);
    }

    public function plus(self $other): self
    {
        $this->assertSameCurrency($other);
        $newAmount = $this->amount->add($other->amount);
        return new self($newAmount, $this->currency);
    }

    public function minus(self $other): self
    {
        $this->assertSameCurrency($other);
        $newAmount = $this->amount->subtract($other->amount);
        return new self($newAmount, $this->currency);
    }

    public function multiply(float|int|string $multiplier): self
    {
        $newAmount = $this->amount->multiply($multiplier);
        return new self($newAmount, $this->currency);
    }

    public function divide(float|int|string $divisor): self
    {
        $newAmount = $this->amount->divide($divisor);
        return new self($newAmount, $this->currency);
    }

    public function percentage(float|int|string $percent): self
    {
        $newAmount = $this->amount->percentage($percent);
        return new self($newAmount, $this->currency);
    }

    public function format(): string
    {
        return $this->amount->__toString() . ' ' . $this->currency->getSymbol();
    }

    public function formatWithIso(): string
    {
        return $this->amount->__toString() . ' ' . $this->currency->value;
    }

    private function assertSameCurrency(self $other): void
    {
        if ($this->currency !== $other->currency) {
            throw new InvalidArgumentException(
                sprintf(
                    'Cannot operate on different currencies: %s and %s',
                    $this->currency->value,
                    $other->currency->value
                )
            );
        }
    }

    public function __toString(): string
    {
        return $this->format();
    }
}

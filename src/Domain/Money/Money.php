<?php

namespace Chip\InterestAccount\Domain\Money;

/**
 * @property float $value
 * @property string $currencyType
 */
class Money
{
    private $value;
    private $currencyType;

    public function __construct(float $value = 0.0, string $currencyType = CurrencyType::GBP)
    {
        $this->value = $value;
        $this->currencyType = $currencyType;
    }

    public function setValue(float $value): Money
    {
        $this->value = $value;
        return $this;
    }

    public function setCurrencyType(string $currencyType): Money
    {
        $this->currencyType = $currencyType;
        return $this;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function getCurrencyType(): string
    {
        return $this->currencyType;
    }
}

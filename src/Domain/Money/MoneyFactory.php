<?php


namespace Chip\InterestAccount\Domain\Money;

class MoneyFactory
{
    public function create(float $amount, string $currencyType): Money
    {
        return new Money($amount, $currencyType);
    }
}

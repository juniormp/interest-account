<?php


namespace Chip\InterestAccount\Domain\Money;

class MoneyFactory
{
    public function create(float $value, string $currencyType): Money
    {
        $money = new Money();

        return $money
            ->setValue($value)
            ->setCurrencyType($currencyType);
    }
}

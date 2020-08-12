<?php

namespace Chip\InterestAccount\Tests\Support;

use Chip\InterestAccount\Domain\Money\CurrencyType;
use Chip\InterestAccount\Domain\Money\Money;
use Chip\InterestAccount\Domain\Money\MoneyFactory;

class MoneySupportFactory
{
    private static $instances = [];

    private static $amount = 0.0;
    private static $currencyType = CurrencyType::GBP;

    public static function getInstance(): MoneySupportFactory
    {
        $cls = static::class;

        if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = new static;
        }

        return self::$instances[$cls];
    }

    public static function destroy()
    {
        self::$instances = null;
    }

    public static function withAmount(float $amount): MoneySupportFactory
    {
        self::$amount = $amount;
        return self::getInstance();
    }

    public static function withCurrencyType(string $currencyType): MoneySupportFactory
    {
        self::$currencyType = $currencyType;
        return self::getInstance();
    }

    public static function build(): Money
    {
        $moneyFactory = new MoneyFactory();
        $money = $moneyFactory->create(self::$amount, self::$currencyType);;

        self::destroy();

        return $money;
    }
}

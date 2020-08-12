<?php


namespace Chip\InterestAccount\Tests\Support;


use Chip\InterestAccount\Domain\InterestRate\InterestRate;
use Chip\InterestAccount\Domain\InterestRate\InterestRateFactory;

class InterestRateSupportFactory
{
    private static $instances = [];

    private static $rate = 0.0;

    public static function getInstance(): InterestRateSupportFactory
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

    public static function withRate(float $rate): InterestRateSupportFactory
    {
        self::$rate = $rate;
        return self::getInstance();
    }

    public static function build(): InterestRate
    {
        $interestRateFactory = new InterestRateFactory();
        $interestRate = $interestRateFactory->create(self::$rate);

        self::destroy();

        return $interestRate;
    }
}

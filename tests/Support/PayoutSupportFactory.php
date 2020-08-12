<?php

namespace Chip\InterestAccount\Tests\Support;

use Chip\InterestAccount\Domain\Money\Money;
use Chip\InterestAccount\Domain\Payout\Payout;
use Chip\InterestAccount\Domain\Payout\PayoutFactory;

class PayoutSupportFactory
{
    private static $instances = [];

    private static $referenceId = "3f950b7d-1f8f-4f86-87cb-ab819ad6cabd";
    private static $amount = null;

    public static function getInstance(): PayoutSupportFactory
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

    public static function withReferenceId(string $referenceId): PayoutSupportFactory
    {
        self::$referenceId = $referenceId;
        return self::getInstance();
    }

    public static function withAmount(Money $amount): PayoutSupportFactory
    {
        self::$amount = $amount;
        return self::getInstance();
    }

    public static function build(): Payout
    {
        $payoutFactory = new PayoutFactory();
        $payout = $payoutFactory->create(self::$referenceId, self::$amount);;

        self::destroy();

        return $payout;
    }
}

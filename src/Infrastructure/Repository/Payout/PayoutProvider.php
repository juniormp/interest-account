<?php

namespace Chip\InterestAccount\Infrastructure\Repository\Payout;

use Chip\InterestAccount\Domain\Payout\Payout;
use Exception;

/**
 * @property array $payouts
 */
class PayoutProvider implements PayoutRepository
{
    private static $instances = [];
    private static $payouts = [];

    protected function __clone()
    {
    }

    public function destroy()
    {
        self::$instances = null;
    }

    public function __wakeup()
    {
        throw new Exception("Cannot unserialize a singleton.");
    }

    public static function getInstance(): PayoutProvider
    {
        $cls = static::class;

        if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = new static;
        }

        return self::$instances[$cls];
    }

    public static function save(Payout $payout): Payout
    {
        array_push(self::$payouts, $payout);

        return $payout;
    }

    public static function getAll(): array
    {
        return self::$payouts;
    }
}

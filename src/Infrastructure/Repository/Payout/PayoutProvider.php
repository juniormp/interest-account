<?php

namespace Chip\InterestAccount\Infrastructure\Repository\Payout;

use ArrayObject;
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

    public static function getAllPayoutsByUserId(string $id): array
    {
        return array_filter(self::$payouts, function ($payout) use ($id) {
            return $payout->getReferenceId() === $id;
        });
    }

    public static function removePayoutByUserId(string $id)
    {
        $payouts = self::getAllPayoutsByUserId($id);

        self::$payouts = array_values(array_diff_key(self::$payouts, $payouts));
    }

    public function cleanPayouts()
    {
        self::$payouts = [];
    }
}

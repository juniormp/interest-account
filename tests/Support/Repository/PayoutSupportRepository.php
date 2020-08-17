<?php

namespace Chip\InterestAccount\Tests\Support\Repository;

use Chip\InterestAccount\Domain\Payout\Payout;
use Chip\InterestAccount\Infrastructure\Repository\Payout\PayoutProvider;

class PayoutSupportRepository
{
    private static $payoutProvider;

    public static function cleanPayoutData(): void
    {
        file_put_contents("payout_repo.txt", "");
    }

    public static function persistPayout(Payout $payout): void
    {
        self::$payoutProvider = new PayoutProvider();
        self::$payoutProvider->save($payout);
    }

    public static function getAll(): array
    {
        self::$payoutProvider = new PayoutProvider();
        return self::$payoutProvider->getAll();
    }
}

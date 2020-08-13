<?php

namespace Chip\InterestAccount\Tests\Support\Repository;

use Chip\InterestAccount\Domain\Payout\Payout;
use Chip\InterestAccount\Infrastructure\Repository\Payout\PayoutProvider;

class PayoutSupportRepository
{
    public static function cleanPayoutData(): void
    {
        PayoutProvider::getInstance()->destroy();
        PayoutProvider::getInstance()->cleanPayouts();
    }

    public static function persistPayout(Payout $payout): void
    {
        PayoutProvider::getInstance()::save($payout);
    }

    public static function getAllPayouts(): array
    {
        return PayoutProvider::getAll();
    }
}

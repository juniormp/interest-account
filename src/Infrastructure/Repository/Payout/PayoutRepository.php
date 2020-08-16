<?php


namespace Chip\InterestAccount\Infrastructure\Repository\Payout;

use Chip\InterestAccount\Domain\Payout\Payout;

interface PayoutRepository
{
    public static function save(Payout $payout): Payout;

    public static function getAllPayoutsByUserId(string $id): array;

    public static function getAll(): array;
}

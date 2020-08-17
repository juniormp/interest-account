<?php


namespace Chip\InterestAccount\Infrastructure\Repository\Payout;

use Chip\InterestAccount\Domain\Payout\Payout;

interface PayoutRepository
{
    public function save(Payout $payout): Payout;

    public function getAllPayoutsByUserId(string $id): array;

    public function getAll(): array;
}

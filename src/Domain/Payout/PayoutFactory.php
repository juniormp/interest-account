<?php


namespace Chip\InterestAccount\Domain\Payout;

use Chip\InterestAccount\Domain\Money\Money;

class PayoutFactory
{
    public function create(string $referenceId, Money $amount): Payout
    {
        return new Payout($referenceId, $amount);
    }
}

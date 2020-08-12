<?php

namespace Chip\InterestAccount\Domain\Payout;

use Chip\InterestAccount\Domain\Money\Money;

/**
 * @property string $referenceId
 * @property Money $amount
 */
class Payout
{
    private $referenceId;
    private $amount;

    public function __construct(string $referenceId, Money $amount)
    {
        $this->referenceId = $referenceId;
        $this->amount = $amount;
    }

    public function getReferenceId(): string
    {
        return $this->referenceId;
    }

    public function getAmount(): Money
    {
        return $this->amount;
    }
}

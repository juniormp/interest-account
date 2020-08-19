<?php

namespace Chip\InterestAccount\Domain\Transaction;

use Chip\InterestAccount\Domain\Money\Money;

/**
 * @covers string $date
 * @covers Money $amount
 */
class Transaction
{
    private $date;
    private $amount;

    public function __construct(string $date, Money $amount)
    {
        $this->date = $date;
        $this->amount = $amount;
    }

    public function getCurrency(): Money
    {
        return $this->amount;
    }

    public function getAmount(): float
    {
        $currency = $this->amount;

        return $currency->getValue();
    }

    public function getDate(): string
    {
        return $this->date;
    }
}

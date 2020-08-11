<?php

namespace Chip\InterestAccount\Domain\Transaction;

use Chip\InterestAccount\Domain\Money\Money;

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
}

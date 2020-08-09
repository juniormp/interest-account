<?php

namespace Chip\InterestAccount\Domain\Account;

use Chip\InterestAccount\Domain\InterestRate\InterestRate;
use Chip\InterestAccount\Domain\Money\Money;

/**
 * @property string $status
 * @property Money $balance
 * @property InterestRate $interestRate
 */
class Account
{
    private $status;
    private $balance;
    private $interestRate;

    public function setStatus(string $status): Account
    {
        $this->status = $status;
        return $this;
    }

    public function setBalance(Money $balance): Account
    {
        $this->balance = $balance;
        return $this;
    }

    public function setInterestRate(InterestRate $interestRate): Account
    {
        $this->interestRate = $interestRate;
        return $this;
    }

    public function getInterestRate(): float
    {
        $interestRate = $this->interestRate;

        return $interestRate->getRate();
    }

    public function applyInterestRate(float $rate): Account
    {
        $interestRate = new InterestRate();
        $interestRate->setRate($rate);

        return $this->setInterestRate($interestRate);
    }
}

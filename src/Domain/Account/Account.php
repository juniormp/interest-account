<?php

namespace Chip\InterestAccount\Domain\Account;

use Chip\InterestAccount\Domain\InterestRate\InterestRate;
use Chip\InterestAccount\Domain\Money\Money;
use Chip\InterestAccount\Domain\Transaction\Transaction;

/**
 * @property string $status
 * @property Money $balance
 * @property InterestRate $interestRate
 * @property array $transactions
 */
class Account
{
    private $status;
    private $balance;
    private $interestRate;
    private $transactions;

    public function __construct()
    {
        $this->transactions = [];
    }

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

    public function getStatus(): string
    {
        return $this->status;
    }

    public function deposit(Money $amount): Transaction
    {
        $transaction = new Transaction(date("Y-m-d H:i:s"), $amount);
        array_push($this->transactions, $transaction);

        return $transaction;
    }

    public function getTransactions(): array
    {
        return $this->transactions;
    }
}

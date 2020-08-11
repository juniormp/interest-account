<?php

namespace Chip\InterestAccount\Domain\User;

use Chip\InterestAccount\Domain\Account\Account;
use Chip\InterestAccount\Domain\Money\Money;

/**
 * @property string $id
 * @property Money $income
 * @property Account $account
 */
class User
{
    private $id;
    private $income;
    private $account;

    public function setId(string $id): User
    {
        $this->id = $id;
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setIncome(Money $income): User
    {
        $this->income = $income;
        return $this;
    }

    public function setAccount(Account $account): User
    {
        $this->account = $account;
        return $this;
    }

    public function getIncome(): Money
    {
        return $this->income;
    }

    public function getAccount(): Account
    {
        return $this->account;
    }

    public function getTransactions(): array
    {
        $account = $this->getAccount();

        return $account->getTransactions();
    }
}

<?php

namespace Chip\InterestAccount\Domain\Account;

use Chip\InterestAccount\Domain\InterestRate\InterestRate;
use Chip\InterestAccount\Domain\Money\Money;

class AccountFactory
{
    public function create(string $status, Money $balance, InterestRate $interestRate): Account
    {
        $account = new Account();

        return $account
            ->setStatus($status)
            ->setBalance($balance)
            ->setInterestRate($interestRate);
    }
}

<?php

namespace Chip\InterestAccount\Domain\Account;

use Chip\InterestAccount\Domain\InterestRate\InterestRate;
use Chip\InterestAccount\Domain\Money\Money;

class AccountFactory
{
    public function create(
        string $referenceId,
        string $status,
        ?Money $balance,
        ?InterestRate $interestRate,
        array $transactions = []
    ): Account {
        $account = new Account();

        return $account
            ->setReferenceId($referenceId)
            ->setStatus($status)
            ->setBalance($balance)
            ->setInterestRate($interestRate)
            ->setTransactions($transactions);
    }
}

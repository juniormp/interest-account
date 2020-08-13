<?php

namespace Chip\InterestAccount\Domain\User;

use Chip\InterestAccount\Domain\Account\Account;
use Chip\InterestAccount\Domain\Account\AccountFactory;
use Chip\InterestAccount\Domain\Account\AccountStatus;
use Chip\InterestAccount\Domain\Money\CurrencyType;
use Chip\InterestAccount\Domain\Money\Money;
use Chip\InterestAccount\Domain\Money\MoneyFactory;

class UserFactory
{
    public function create(string $id, ?Money $income, ?Account $account): User
    {
        $user = new User();

        return $user
            ->setId($id)
            ->setIncome($income)
            ->setAccount($account);
    }
}

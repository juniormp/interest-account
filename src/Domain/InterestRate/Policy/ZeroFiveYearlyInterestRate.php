<?php

namespace Chip\InterestAccount\Domain\InterestRate\Policy;

use Chip\InterestAccount\Domain\Account\Account;
use Chip\InterestAccount\Domain\User\User;

class ZeroFiveYearlyInterestRate implements Policy
{
    const ZERO_FIVE = 0.5;
    const INCOME_ZERO = 0.0;

    public function apply(User $user): Account
    {
        $account = $user->getAccount();
        $income = $user->getIncome();

        if ($income->getValue() === self::INCOME_ZERO) {
            return $account->applyInterestRate(self::ZERO_FIVE);
        }

        return $account;
    }
}

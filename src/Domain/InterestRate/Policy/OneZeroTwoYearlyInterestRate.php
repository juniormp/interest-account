<?php

namespace Chip\InterestAccount\Domain\InterestRate\Policy;

use Chip\InterestAccount\Domain\Account\Account;
use Chip\InterestAccount\Domain\User\User;

class OneZeroTwoYearlyInterestRate implements Policy
{
    const ONE_ZERO_TWO = 1.02;
    const INCOME_ZERO = 0.0;
    const INCOME_FIVE_THOUSAND = 5000.00;

    public function apply(User $user): Account
    {
        $account = $user->getAccount();
        $income = $user->getIncome();

        if ($income->getValue() >= self::INCOME_FIVE_THOUSAND && $income->getValue() !== self::INCOME_ZERO) {
            return $account->applyInterestRate(self::ONE_ZERO_TWO);
        }

        return $account;
    }
}

<?php

namespace Chip\InterestAccount\Domain\InterestRate\Policy;

use Chip\InterestAccount\Domain\Account\Account;
use Chip\InterestAccount\Domain\User\User;

class ZeroNinetyThreeYearlyInterestRate implements Policy
{
    const ZERO_NINETY_THREE = 0.93;
    const INCOME_ZERO = 0.0;
    const INCOME_FIVE_THOUSAND = 5000.00;

    public function apply(User $user): Account
    {
        $account = $user->getAccount();
        $income = $user->getIncome();

        if ($income->getValue() < self::INCOME_FIVE_THOUSAND && $income->getValue() !== self::INCOME_ZERO) {
            return $account->applyInterestRate(self::ZERO_NINETY_THREE);
        }

        return $account;
    }
}

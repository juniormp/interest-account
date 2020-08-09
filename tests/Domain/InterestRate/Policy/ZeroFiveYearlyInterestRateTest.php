<?php

use Chip\InterestAccount\Domain\Account\Account;
use Chip\InterestAccount\Domain\InterestRate\InterestRate;
use Chip\InterestAccount\Domain\InterestRate\Policy\ZeroFiveYearlyInterestRate;
use Chip\InterestAccount\Domain\Money\Money;
use Chip\InterestAccount\Domain\User\User;
use PHPUnit\Framework\TestCase;

class ZeroFiveYearlyInterestRateTest extends TestCase
{
    public function test_should_apply_rate_when_income_is_equal_to_zero()
    {
        $subject = new ZeroFiveYearlyInterestRate();
        $income = 0.0;
        $rateApplied = ZeroFiveYearlyInterestRate::ZERO_FIVE;
        $account = $this->buildAccount();
        $user = $this->buildUser($account, $income);

        $result = $subject->apply($user);

        $this->assertEquals($rateApplied, $result->getInterestRate());
    }

    public function test_should_not_apply_rate_when_income_is_not_equal_to_zero()
    {
        $subject = new ZeroFiveYearlyInterestRate();
        $income = 0.1;
        $rateNotApplied = 0.0;
        $account = $this->buildAccount();
        $user = $this->buildUser($account, $income);

        $result = $subject->apply($user);

        $this->assertEquals($rateNotApplied, $result->getInterestRate());
    }

    public function buildUser(Account $account, float $value): User
    {
        $user = new User();
        $income = new Money();
        $income->setValue($value);

        return $user
            ->setAccount($account)
            ->setIncome($income);
    }

    public function buildAccount(): Account
    {
        $account = new Account();
        $interestRate = new InterestRate();
        $interestRate
            ->setRate(0.0);

        return $account
            ->setInterestRate($interestRate);
    }
}

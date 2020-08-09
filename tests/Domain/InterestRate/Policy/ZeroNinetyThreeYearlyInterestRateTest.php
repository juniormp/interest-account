<?php

use Chip\InterestAccount\Domain\Account\Account;
use Chip\InterestAccount\Domain\InterestRate\InterestRate;
use Chip\InterestAccount\Domain\InterestRate\Policy\ZeroNinetyThreeYearlyInterestRate;
use Chip\InterestAccount\Domain\Money\Money;
use Chip\InterestAccount\Domain\User\User;
use PHPUnit\Framework\TestCase;

class ZeroNinetyThreeYearlyInterestRateTest extends TestCase
{
    public function test_should_apply_rate_when_income_is_less_than_five_thousand()
    {
        $subject = new ZeroNinetyThreeYearlyInterestRate();
        $income = 4999.99;
        $rateApplied = ZeroNinetyThreeYearlyInterestRate::ZERO_NINETY_THREE;
        $account = $this->buildAccount();
        $user = $this->buildUser($account, $income);

        $result = $subject->apply($user);

        $this->assertEquals($rateApplied, $result->getInterestRate());
    }

    public function test_should_not_apply_rate_when_income_is_equal_to_zero()
    {
        $subject = new ZeroNinetyThreeYearlyInterestRate();
        $income = 0.0;
        $rateNotApplied = 0.0;
        $account = $this->buildAccount();
        $user = $this->buildUser($account, $income);

        $result = $subject->apply($user);

        $this->assertEquals($rateNotApplied, $result->getInterestRate());
    }

    public function test_should_not_apply_rate_when_income_is_equal_to_five_thousand()
    {
        $subject = new ZeroNinetyThreeYearlyInterestRate();
        $income = 5000.00;
        $rateNotApplied = 0.0;
        $account = $this->buildAccount();
        $user = $this->buildUser($account, $income);

        $result = $subject->apply($user);

        $this->assertEquals($rateNotApplied, $result->getInterestRate());
    }

    public function test_should_not_apply_rate_when_income_is_greater_than_five_thousand()
    {
        $subject = new ZeroNinetyThreeYearlyInterestRate();
        $income = 5000.01;
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
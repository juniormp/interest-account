<?php

use Chip\InterestAccount\Domain\InterestRate\Policy\OneZeroTwoYearlyInterestRate;
use Chip\InterestAccount\Tests\Support\AccountSupportFactory;
use Chip\InterestAccount\Tests\Support\InterestRateSupportFactory;
use Chip\InterestAccount\Tests\Support\MoneySupportFactory;
use Chip\InterestAccount\Tests\Support\UserSupportFactory;
use PHPUnit\Framework\TestCase;

class OneZeroTwoYearlyInterestRateTest extends TestCase
{
    public function test_should_apply_rate_when_income_is_greater_than_five_thousand()
    {
        $subject = new OneZeroTwoYearlyInterestRate();
        $rateApplied = OneZeroTwoYearlyInterestRate::ONE_ZERO_TWO;
        $interestRate = InterestRateSupportFactory::getInstance()::withRate(0)::build();
        $income = MoneySupportFactory::getInstance()::withAmount(5001.00)::build();
        $account = AccountSupportFactory::getInstance()::withInterestRate($interestRate)::build();
        $user = UserSupportFactory::getInstance()::withIncome($income)::withAccount($account)::build();

        $result = $subject->apply($user);

        $this->assertEquals($rateApplied, $result->getInterestRate());
    }

    public function test_should_apply_rate_when_income_is_equal_to_five_thousand()
    {
        $subject = new OneZeroTwoYearlyInterestRate();
        $rateApplied = OneZeroTwoYearlyInterestRate::ONE_ZERO_TWO;
        $interestRate = InterestRateSupportFactory::getInstance()::withRate(0)::build();
        $income = MoneySupportFactory::getInstance()::withAmount(5000.00)::build();
        $account = AccountSupportFactory::getInstance()::withInterestRate($interestRate)::build();
        $user = UserSupportFactory::getInstance()::withIncome($income)::withAccount($account)::build();

        $result = $subject->apply($user);

        $this->assertEquals($rateApplied, $result->getInterestRate());
    }

    public function test_should_not_apply_rate_when_income_is_less_than_five_thousand()
    {
        $subject = new OneZeroTwoYearlyInterestRate();
        $interestRate = InterestRateSupportFactory::getInstance()::withRate(0)::build();
        $income = MoneySupportFactory::getInstance()::withAmount(4999.9)::build();
        $account = AccountSupportFactory::getInstance()::withInterestRate($interestRate)::build();
        $user = UserSupportFactory::getInstance()::withIncome($income)::withAccount($account)::build();
        $rateNotApplied = 0.0;

        $result = $subject->apply($user);

        $this->assertEquals($rateNotApplied, $result->getInterestRate());
    }

    public function test_should_not_apply_rate_when_income_is_equal_to_zero()
    {
        $subject = new OneZeroTwoYearlyInterestRate();
        $interestRate = InterestRateSupportFactory::getInstance()::withRate(0)::build();
        $income = MoneySupportFactory::getInstance()::withAmount(0.0)::build();
        $account = AccountSupportFactory::getInstance()::withInterestRate($interestRate)::build();
        $user = UserSupportFactory::getInstance()::withIncome($income)::withAccount($account)::build();
        $rateNotApplied = 0.0;

        $result = $subject->apply($user);

        $this->assertEquals($rateNotApplied, $result->getInterestRate());
    }
}

<?php

use Chip\InterestAccount\Domain\InterestRate\Policy\ZeroFiveYearlyInterestRate;
use Chip\InterestAccount\Tests\Support\AccountSupportFactory;
use Chip\InterestAccount\Tests\Support\InterestRateSupportFactory;
use Chip\InterestAccount\Tests\Support\MoneySupportFactory;
use Chip\InterestAccount\Tests\Support\UserSupportFactory;
use PHPUnit\Framework\TestCase;

class ZeroFiveYearlyInterestRateTest extends TestCase
{
    public function test_should_apply_rate_when_income_is_equal_to_zero()
    {
        $subject = new ZeroFiveYearlyInterestRate();
        $rateApplied = ZeroFiveYearlyInterestRate::ZERO_FIVE;
        $interestRate = InterestRateSupportFactory::getInstance()::withRate(0)::build();
        $income = MoneySupportFactory::getInstance()::withAmount(0)::build();
        $account = AccountSupportFactory::getInstance()::withInterestRate($interestRate)::build();
        $user = UserSupportFactory::getInstance()::withIncome($income)::withAccount($account)::build();

        $result = $subject->apply($user);

        $this->assertEquals($rateApplied, $result->getInterestRate());
    }

    public function test_should_not_apply_rate_when_income_is_not_equal_to_zero()
    {
        $subject = new ZeroFiveYearlyInterestRate();
        $interestRate = InterestRateSupportFactory::getInstance()::withRate(0)::build();
        $income = MoneySupportFactory::getInstance()::withAmount(0.1)::build();
        $account = AccountSupportFactory::getInstance()::withInterestRate($interestRate)::build();
        $user = UserSupportFactory::getInstance()::withIncome($income)::withAccount($account)::build();
        $rateNotApplied = 0.0;

        $result = $subject->apply($user);

        $this->assertEquals($rateNotApplied, $result->getInterestRate());
    }
}

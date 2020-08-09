<?php

use Chip\InterestAccount\Domain\InterestRate\InterestRatePolicyBuilder;
use Chip\InterestAccount\Domain\InterestRate\Policy\OneZeroTwoYearlyInterestRate;
use Chip\InterestAccount\Domain\InterestRate\Policy\ZeroFiveYearlyInterestRate;
use Chip\InterestAccount\Domain\InterestRate\Policy\ZeroNinetyThreeYearlyInterestRate;
use PHPUnit\Framework\TestCase;

class InterestRatePolicyBuilderTest extends TestCase
{
    public function test_should_build_policies_in_the_follow_order()
    {
        $subject = new InterestRatePolicyBuilder();
        $zeroFiveYearlyInterestRate = new ZeroFiveYearlyInterestRate();
        $zeroNinetyThreeYearlyInterestRate = new ZeroNinetyThreeYearlyInterestRate();
        $oneZeroTwoYearlyInterestRate = new OneZeroTwoYearlyInterestRate();

        $result = $subject->build();

        $this->assertEquals($zeroFiveYearlyInterestRate, $result[0]);
        $this->assertEquals($zeroNinetyThreeYearlyInterestRate, $result[1]);
        $this->assertEquals($oneZeroTwoYearlyInterestRate, $result[2]);
    }
}

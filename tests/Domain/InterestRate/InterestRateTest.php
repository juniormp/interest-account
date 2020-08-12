<?php


use Chip\InterestAccount\Domain\InterestRate\InterestRate;
use PHPUnit\Framework\TestCase;

class InterestRateTest extends TestCase
{
    public function test_convert_annual_rate_to_three_days_rate()
    {
        $subject = new InterestRate();
        $annualRate = 1.02;
        $subject->setRate($annualRate);

        $result = $subject->convertAnnualRateToThreeDaysRate();

        $this->assertEquals( 8.4573001526422E-5, $result);
    }
}

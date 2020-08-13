<?php


use Chip\InterestAccount\Domain\InterestRate\InterestRateFactory;
use Chip\InterestAccount\Tests\Support\InterestRateSupportFactory;
use PHPUnit\Framework\TestCase;

class InterestRateFactoryTest extends TestCase
{
    public function test_should_return_interest_rate_with_the_correct_data()
    {
        $subject = new InterestRateFactory();
        $interestRate = InterestRateSupportFactory::getInstance()::withRate(0.5)::build();

        $result = $subject->create($interestRate->getAnnualRate());

        $this->assertEquals($interestRate, $result);
    }
}

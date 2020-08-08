<?php


use Chip\InterestAccount\Domain\InterestRate\InterestRate;
use Chip\InterestAccount\Domain\InterestRate\InterestRateFactory;
use PHPUnit\Framework\TestCase;

class InterestRateFactoryTest extends TestCase
{
    public function test_should_return_interest_rate_with_the_correct_data()
    {
        $subject = new InterestRateFactory();
        $interestRate = new InterestRate();
        $interestRate->setRate(0.5);

        $result = $subject->create($interestRate->getRate());

        $this->assertEquals($interestRate, $result);
    }
}

<?php


namespace Chip\InterestAccount\Domain\InterestRate;

class InterestRateFactory
{
    public function create(float $rate): InterestRate
    {
        $interestRate = new InterestRate();

        return $interestRate->setRate($rate);
    }
}

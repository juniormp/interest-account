<?php

namespace Chip\InterestAccount\Domain\InterestRate;

/**
 * @property float $rate
 */
class InterestRate
{
    private $rate;

    public function setRate(float $rate): InterestRate
    {
        $this->rate = $rate;
        return $this;
    }

    public function getRate(): float
    {
        return $this->rate;
    }

    public function convertAnnualRateToThreeDaysRate(): float
    {
        return pow((1 + pow((1 + $this->getRate() / 100), 1/12) - 1), 1/10) - 1;
    }
}

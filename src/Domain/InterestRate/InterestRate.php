<?php

namespace Chip\InterestAccount\Domain\InterestRate;

/**
 * @property float $annualRate
 */
class InterestRate
{
    private $annualRate;

    public function setAnnualRate(float $annualRate): InterestRate
    {
        $this->annualRate = $annualRate;
        return $this;
    }

    public function getAnnualRate(): float
    {
        return $this->annualRate;
    }

    public function convertAnnualRateToThreeDaysRate(): float
    {
        return pow((1 + pow((1 + $this->getAnnualRate() / 100), 1/12) - 1), 1/10) - 1;
    }
}

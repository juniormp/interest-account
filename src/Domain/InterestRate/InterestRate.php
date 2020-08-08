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
}

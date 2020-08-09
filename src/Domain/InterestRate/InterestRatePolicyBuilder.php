<?php

namespace Chip\InterestAccount\Domain\InterestRate;

use Chip\InterestAccount\Domain\InterestRate\Policy\OneZeroTwoYearlyInterestRate;
use Chip\InterestAccount\Domain\InterestRate\Policy\ZeroFiveYearlyInterestRate;
use Chip\InterestAccount\Domain\InterestRate\Policy\ZeroNinetyThreeYearlyInterestRate;

class InterestRatePolicyBuilder
{
    public function build(): array
    {
        return [
            new ZeroFiveYearlyInterestRate(),
            new ZeroNinetyThreeYearlyInterestRate(),
            new OneZeroTwoYearlyInterestRate()
        ];
    }
}

<?php

namespace Chip\InterestAccount\Application\Response;

use Chip\InterestAccount\Domain\InterestRate\InterestRate;

class InterestRateResponse
{
    public static function toJson(InterestRate $interestRate)
    {
        return [
            "annualRate" => $interestRate->getAnnualRate()
        ];
    }
}

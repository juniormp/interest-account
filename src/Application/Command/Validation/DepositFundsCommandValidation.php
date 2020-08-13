<?php

namespace Chip\InterestAccount\Application\Command\Validation;

use Chip\InterestAccount\Application\Command\Validation\Rules\IDFormatRule;
use Chip\InterestAccount\Application\Command\Validation\Rules\NegativeAmountRule;

class DepositFundsCommandValidation
{
    public static function validate(string $id, float $amount): void
    {
        IDFormatRule::validate($id);
        NegativeAmountRule::validate($amount);
    }
}

<?php

namespace Chip\InterestAccount\Application\Command\Validation;

use Chip\InterestAccount\Application\Command\Validation\Rules\IDFormatRule;

class CalculatePayoutCommandValidation
{
    public static function validate(string $id): void
    {
        IDFormatRule::validate($id);
    }
}

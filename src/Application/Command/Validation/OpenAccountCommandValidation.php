<?php

namespace Chip\InterestAccount\Application\Command\Validation;

use Chip\InterestAccount\Application\Command\Validation\Rules\IDFormatRule;

class OpenAccountCommandValidation
{
    public static function validate(string $id): void
    {
        IDFormatRule::validate($id);
    }
}

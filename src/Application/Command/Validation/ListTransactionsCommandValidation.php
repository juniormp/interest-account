<?php

namespace Chip\InterestAccount\Application\Command\Validation;

use Chip\InterestAccount\Application\Command\Validation\Rules\IDFormatRule;

class ListTransactionsCommandValidation
{
    public static function validate(string $id): void
    {
        IDFormatRule::validate($id);
    }
}

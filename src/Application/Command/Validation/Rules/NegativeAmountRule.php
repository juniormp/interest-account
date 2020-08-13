<?php


namespace Chip\InterestAccount\Application\Command\Validation\Rules;

use Chip\InterestAccount\Application\Command\Validation\ValidationError;

class NegativeAmountRule
{
    private const ZERO = 0;

    public static function validate(float $amount): void
    {
        if ($amount < self::ZERO) {
            throw new ValidationError(ValidationError::NEGATIVE_AMOUNT);
        }
    }
}

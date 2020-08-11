<?php

namespace Chip\InterestAccount\Application\Command\Validation;

class DepositFundsCommandValidation
{
    const UUIDv4_FORMAT = '/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i';

    public static function validate(string $id, float $amount)
    {
        $result = preg_match(self::UUIDv4_FORMAT, $id);

        if ($result !== 1) {
            throw new ValidationError(ValidationError::ID_FORMAT);
        }

        if ($amount < 0.0) {
            throw new ValidationError(ValidationError::NEGATIVE_AMOUNT);
        }
    }
}

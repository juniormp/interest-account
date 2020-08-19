<?php

namespace Chip\InterestAccount\Application\Response;

use Chip\InterestAccount\Domain\Money\Money;

class MoneyResponse
{
    public static function toJson(Money $money)
    {
        return [
            "value" => $money->getValue(),
            "currencyType" => $money->getCurrencyType()
        ];
    }
}

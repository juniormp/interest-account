<?php

namespace Chip\InterestAccount\Application\Response;

use Chip\InterestAccount\Domain\Account\Account;

class AccountResponse
{
    public static function toJson(Account $account)
    {
        return [
            "account" => [
                "referenceId" => $account->getReferenceId(),
                "status" => $account->getStatus(),
                "balance" => MoneyResponse::toJson($account->getBalance()),
                "interestRate" => InterestRateResponse::toJson($account->getInterestRateEntity()),
            ]
        ];
    }
}

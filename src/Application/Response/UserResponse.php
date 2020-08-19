<?php

namespace Chip\InterestAccount\Application\Response;

use Chip\InterestAccount\Domain\User\User;

class UserResponse
{
    public static function toJson(User $user)
    {
        return [
            "user" => [
                "id" => $user->getId(),
                "income" => MoneyResponse::toJson($user->getIncome()),
                "account" => AccountResponse::toJson($user->getAccount()),
            ]
        ];
    }
}

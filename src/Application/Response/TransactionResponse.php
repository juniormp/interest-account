<?php

namespace Chip\InterestAccount\Application\Response;

use Chip\InterestAccount\Domain\Transaction\Transaction;

class TransactionResponse
{
    public static function toJson(Transaction $transaction): array
    {
        return [
            "date" => $transaction->getDate(),
            "amount" => MoneyResponse::toJson($transaction->getCurrency())
        ];
    }
}

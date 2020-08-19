<?php

namespace Chip\InterestAccount\Application\Response;

use Chip\InterestAccount\Domain\Transaction\Transaction;

class TransactionResponse
{
    public static function toJson(Transaction $transaction)
    {
        return [
            "date" => $transaction->getAmount(),
            "amount" => MoneyResponse::toJson($transaction->getCurrency())
        ];
    }
}

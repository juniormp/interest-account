<?php


namespace Chip\InterestAccount\Application\Response;

class TransactionsResponse
{
    public static function toJson(array $transactions): array
    {
        $transactionsArray = [];

        foreach ($transactions as $transaction) {
            array_push($transactionsArray, TransactionResponse::toJson($transaction));
        }

        return $transactionsArray;
    }
}

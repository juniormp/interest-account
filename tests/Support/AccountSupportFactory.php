<?php


namespace Chip\InterestAccount\Tests\Support;


use Chip\InterestAccount\Domain\Account\Account;
use Chip\InterestAccount\Domain\Account\AccountFactory;
use Chip\InterestAccount\Domain\Account\AccountStatus;
use Chip\InterestAccount\Domain\InterestRate\InterestRate;
use Chip\InterestAccount\Domain\Money\Money;

class AccountSupportFactory
{
    private static $instances = [];

    private static $referenceId = "3f950b7d-1f8f-4f86-87cb-ab819ad6cabd";
    private static $status = AccountStatus::ACTIVE;
    private static $balance = null;
    private static $interestRate = null;
    private static $transactions = [];

    public static function getInstance(): AccountSupportFactory
    {
        $cls = static::class;

        if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = new static;
        }

        return self::$instances[$cls];
    }

    public static function destroy()
    {
        self::$instances = null;
    }

    public static function withStatus(string $status): AccountSupportFactory
    {
        self::$status = $status;
        return self::getInstance();
    }

    public static function withBalance(Money $balance): AccountSupportFactory
    {
        self::$balance = $balance;
        return self::getInstance();
    }

    public static function withInterestRate(InterestRate $interestRate): AccountSupportFactory
    {
        self::$interestRate = $interestRate;
        return self::getInstance();
    }

    public static function withTransactions(array $transactions): AccountSupportFactory
    {
        self::$transactions = $transactions;
        return self::getInstance();
    }

    public static function withReferenceId(string $referenceId): AccountSupportFactory
    {
        self::$referenceId = $referenceId;
        return self::getInstance();
    }

    public static function build(): Account
    {
        $accountFactory = new AccountFactory();
        $account = $accountFactory->create(
            self::$referenceId, self::$status, self::$balance, self::$interestRate, self::$transactions);

        self::destroy();

        return $account;
    }
}

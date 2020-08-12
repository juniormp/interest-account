<?php


namespace Chip\InterestAccount\Tests\Support;


use Chip\InterestAccount\Domain\Account\Account;
use Chip\InterestAccount\Domain\Money\Money;
use Chip\InterestAccount\Domain\User\User;
use Chip\InterestAccount\Domain\User\UserFactory;

class UserSupportFactory
{
    private static $instances = [];

    private static $id = "3f950b7d-1f8f-4f86-87cb-ab819ad6cabd";
    private static $income = null;
    private static $account = null;

    public static function getInstance(): UserSupportFactory
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

    public static function withId(string $id): UserSupportFactory
    {
        self::$id = $id;
        return self::getInstance();
    }

    public static function withIncome(Money $income): UserSupportFactory
    {
        self::$income = $income;
        return self::getInstance();
    }

    public static function withAccount(Account $account): UserSupportFactory
    {
        self::$account = $account;
        return self::getInstance();
    }

    public static function build(): User
    {
        $userFactory = new UserFactory();
        $user = $userFactory->create(self::$id, self::$income, self::$account);

        self::destroy();

        return $user;
    }
}

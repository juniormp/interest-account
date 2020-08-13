<?php

use Chip\InterestAccount\Domain\Account\Account;
use Chip\InterestAccount\Domain\Account\AccountStatus;
use Chip\InterestAccount\Domain\InterestRate\InterestRate;
use Chip\InterestAccount\Domain\User\User;
use Chip\InterestAccount\Domain\User\UUID;
use Chip\InterestAccount\Domain\Money\CurrencyType;
use Chip\InterestAccount\Domain\Money\Money;
use Chip\InterestAccount\Domain\User\UserFactory;
use PHPUnit\Framework\TestCase;

class UserFactoryTest extends TestCase
{
    public function test_should_return_user_with_the_correct_data()
    {
        $id = UUID::v4();
        $subject = new UserFactory();
        $status = AccountStatus::ACTIVE;
        $balance = $this->buildBalance();
        $interestRate = $this->buildInterestRate();
        $account = $this->buildAccount($status, $balance, $interestRate);
        $user = $this->buildUser($id, $account);

        $result = $subject->create($id, $user->getIncome(), $account);

        $this->assertEquals($user, $result);
    }

    private function buildUser(string $id, Account $account): User
    {
        $income = new Money();
        $income
            ->setValue(500.00)
            ->setCurrencyType(CurrencyType::GBP);

        $user = new User();
        $user
            ->setId($id)
            ->setIncome($income)
            ->setAccount($account);


        return $user;
    }

    private function buildAccount(string $status, Money $balance, InterestRate $interestRate): Account
    {
        $account = new Account();
        $account
            ->setStatus($status)
            ->setBalance($balance)
            ->setInterestRate($interestRate);

        return $account;
    }

    public function buildBalance(): Money
    {
        $balance = new Money();

        return $balance
            ->setValue(5000.00)
            ->setCurrencyType(CurrencyType::GBP);
    }

    public function buildInterestRate(): InterestRate
    {
        $interestRate = new InterestRate();

        return $interestRate->setAnnualRate(0.5);
    }
}

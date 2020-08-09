<?php

use Chip\InterestAccount\Domain\Account\Account;
use Chip\InterestAccount\Domain\Account\AccountFactory;
use Chip\InterestAccount\Domain\Account\AccountStatus;
use Chip\InterestAccount\Domain\InterestRate\InterestRate;
use Chip\InterestAccount\Domain\Money\CurrencyType;
use Chip\InterestAccount\Domain\Money\Money;
use PHPUnit\Framework\TestCase;

class AccountFactoryTest extends TestCase
{
    public function test_should_return_account_with_the_correct_data()
    {
        $subject = new AccountFactory();
        $status = AccountStatus::ACTIVE;
        $balance = $this->buildBalance();
        $interestRate = $this->buildInterestRate();
        $account = $this->buildAccount($status, $balance, $interestRate);

        $result = $subject->create($status, $balance, $interestRate);

        $this->assertEquals($account, $result);
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

        return $interestRate->setRate(0.5);
    }
}

<?php

use Chip\InterestAccount\Domain\Account\AccountFactory;
use Chip\InterestAccount\Domain\Account\AccountStatus;
use Chip\InterestAccount\Tests\Support\AccountSupportFactory;
use Chip\InterestAccount\Tests\Support\InterestRateSupportFactory;
use Chip\InterestAccount\Tests\Support\MoneySupportFactory;
use PHPUnit\Framework\TestCase;

class AccountFactoryTest extends TestCase
{
    public function test_should_return_account_with_the_correct_data()
    {
        $subject = new AccountFactory();
        $referenceId = "3f950b7d-1f8f-4f86-87cb-ab819ad6cabd";
        $status = AccountStatus::ACTIVE;
        $interestRate = InterestRateSupportFactory::getInstance()::withRate(0.5)::build();
        $balance = MoneySupportFactory::getInstance()::withAmount(5000.00)::build();
        $account = AccountSupportFactory::getInstance()::withReferenceId($referenceId)::withBalance($balance)
            ::withStatus($status)::withInterestRate($interestRate)::build();

        $result = $subject->create($referenceId, $status, $balance, $interestRate);

        $this->assertEquals($account, $result);
    }
}

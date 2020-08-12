<?php


use Chip\InterestAccount\Domain\Account\Account;
use Chip\InterestAccount\Tests\Support\MoneySupportFactory;
use PHPUnit\Framework\TestCase;

class AccountTest extends TestCase
{
    public function test_should_deposit_amount_into_account()
    {
        $subject = new Account();
        $amount = MoneySupportFactory::getInstance()::withAmount(500.00)::build();
        $amount2 = MoneySupportFactory::getInstance()::withAmount(5250.00)::build();
        $transaction = $subject->deposit($amount);
        $transaction2 = $subject->deposit($amount2);

        $transactions = $subject->getTransactions();

        $this->assertCount(2, $transactions);
        $this->assertEquals($amount, $transaction->getCurrency());
        $this->assertEquals($amount2, $transaction2->getCurrency());
    }
}

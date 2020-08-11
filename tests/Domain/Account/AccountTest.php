<?php


use Chip\InterestAccount\Domain\Account\Account;
use Chip\InterestAccount\Domain\Money\CurrencyType;
use Chip\InterestAccount\Domain\Money\MoneyFactory;
use PHPUnit\Framework\TestCase;

class AccountTest extends TestCase
{
    public function test_should_deposit_amount_into_account()
    {
        $subject = new Account();
        $moneyFactory = new MoneyFactory();
        $money = $moneyFactory->create(500.0, CurrencyType::GBP);
        $money2 = $moneyFactory->create(250.0, CurrencyType::GBP);
        $transaction = $subject->deposit($money);
        $transaction2 = $subject->deposit($money2);

        $transactions = $subject->getTransactions();

        $this->assertCount(2, $transactions);
        $this->assertEquals($money, $transaction->getAmount());
        $this->assertEquals($money2, $transaction2->getAmount());
    }
}

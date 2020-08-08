<?php


use Chip\InterestAccount\Domain\Money\CurrencyType;
use Chip\InterestAccount\Domain\Money\Money;
use Chip\InterestAccount\Domain\Money\MoneyFactory;
use PHPUnit\Framework\TestCase;

class MoneyFactoryTest extends TestCase
{
    public function test_should_return_money_with_the_correct_data()
    {
        $subject = new MoneyFactory();
        $money = new Money();
        $money
            ->setValue(10.0)
            ->setCurrencyType(CurrencyType::GBP);

        $result = $subject->create($money->getValue(), $money->getCurrencyType());

        $this->assertEquals($money, $result);
    }
}

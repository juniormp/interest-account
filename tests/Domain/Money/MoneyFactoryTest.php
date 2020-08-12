<?php


use Chip\InterestAccount\Domain\Money\CurrencyType;
use Chip\InterestAccount\Domain\Money\MoneyFactory;
use Chip\InterestAccount\Tests\Support\MoneySupportFactory;
use PHPUnit\Framework\TestCase;

class MoneyFactoryTest extends TestCase
{
    public function test_should_return_money_with_the_correct_data()
    {
        $subject = new MoneyFactory();
        $money = MoneySupportFactory::getInstance()
            ::withAmount(10.0)::withCurrencyType(CurrencyType::GBP)::build();

        $result = $subject->create($money->getValue(), $money->getCurrencyType());

        $this->assertEquals($money, $result);
    }
}

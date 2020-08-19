<?php


use Chip\InterestAccount\Application\Response\MoneyResponse;
use Chip\InterestAccount\Domain\Money\CurrencyType;
use Chip\InterestAccount\Domain\Money\Money;
use Chip\InterestAccount\Tests\Support\MoneySupportFactory;
use PHPUnit\Framework\TestCase;

class MoneyResponseTest extends TestCase
{
    public function test_should_money_account_to_json()
    {
        $money = $this->buildMoney();
        $expect = $this->json();

        $result = MoneyResponse::toJson($money);

        $this->assertSame($expect, $result);
    }

    private function buildMoney(): Money
    {
        return MoneySupportFactory::getInstance()::withAmount(200.0)
            ::withCurrencyType(CurrencyType::GBP)::build();
    }

    private function json(): array
    {
        return [
            "value" => 200.00,
            "currencyType" => "GBP - British pound"
        ];
    }
}

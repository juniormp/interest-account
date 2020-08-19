<?php


use Chip\InterestAccount\Application\Response\AccountResponse;
use Chip\InterestAccount\Domain\Account\Account;
use Chip\InterestAccount\Domain\Account\AccountStatus;
use Chip\InterestAccount\Domain\Money\CurrencyType;
use Chip\InterestAccount\Tests\Support\AccountSupportFactory;
use Chip\InterestAccount\Tests\Support\InterestRateSupportFactory;
use Chip\InterestAccount\Tests\Support\MoneySupportFactory;
use PHPUnit\Framework\TestCase;

class AccountResponseTest extends TestCase
{
    public function test_should_convert_account_to_json()
    {
        $account = $this->buildAccount();
        $expect = $this->json();

        $result = AccountResponse::toJson($account);

        $this->assertSame($expect, $result);
    }

    private function buildAccount(): Account
    {
        $id = "7693cb14-6f68-4be5-8a4a-9fc3740f6754";

        $balance = MoneySupportFactory::getInstance()::withCurrencyType(CurrencyType::GBP)::build();

        $interestRate = InterestRateSupportFactory::getInstance()::withRate(1.02)::build();

        return AccountSupportFactory::getInstance()::withReferenceId($id)::withStatus(AccountStatus::ACTIVE)
            ::withBalance($balance)::withInterestRate($interestRate)::build();

    }

    private function json(): array
    {
        return [
            "referenceId" => "7693cb14-6f68-4be5-8a4a-9fc3740f6754",
            "status" => "ACTIVE",
            "balance" => [
                "value" => 0.0,
                "currencyType" => "GBP - British pound"
            ],
            "interestRate" => [
                "annualRate" => 1.02
            ]
        ];
    }
}

<?php


use Chip\InterestAccount\Application\Response\UserResponse;
use Chip\InterestAccount\Domain\Account\AccountStatus;
use Chip\InterestAccount\Domain\Money\CurrencyType;
use Chip\InterestAccount\Domain\User\User;
use Chip\InterestAccount\Tests\Support\AccountSupportFactory;
use Chip\InterestAccount\Tests\Support\InterestRateSupportFactory;
use Chip\InterestAccount\Tests\Support\MoneySupportFactory;
use Chip\InterestAccount\Tests\Support\UserSupportFactory;
use PHPUnit\Framework\TestCase;

class UserResponseTest extends TestCase
{
    public function test_should_convert_user_to_json()
    {
        $user = $this->buildUser();
        $expect = $this->json();

        $result = UserResponse::toJson($user);

        $this->assertSame($expect, $result);
    }

    private function buildUser(): User
    {
        $id = "7693cb14-6f68-4be5-8a4a-9fc3740f6754";

        $income = MoneySupportFactory::getInstance()::withAmount(200.0)
            ::withCurrencyType(CurrencyType::GBP)::build();

        $balance = MoneySupportFactory::getInstance()::withCurrencyType(CurrencyType::GBP)::build();

        $interestRate = InterestRateSupportFactory::getInstance()::withRate(1.02)::build();

        $account = AccountSupportFactory::getInstance()::withReferenceId($id)::withStatus(AccountStatus::ACTIVE)
            ::withBalance($balance)::withInterestRate($interestRate)::build();

        return UserSupportFactory::getInstance()::withId($id)
            ::withIncome($income)::withAccount($account)::build();
    }

    private function json(): array
    {
        return [
            "user" => [
                "id" => "7693cb14-6f68-4be5-8a4a-9fc3740f6754",
                "income" => [
                    "value" => 200.0,
                    "currencyType" => "GBP - British pound"
                ],
                "account" => [
                    "referenceId" => "7693cb14-6f68-4be5-8a4a-9fc3740f6754",
                    "status" => "ACTIVE",
                    "balance" => [
                        "value" => 0.0,
                        "currencyType" => "GBP - British pound"
                    ],
                    "interestRate" => [
                        "annualRate" => 1.02
                    ]
                ]
            ]
        ];
    }
}

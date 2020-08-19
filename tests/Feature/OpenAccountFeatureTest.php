<?php

use Chip\InterestAccount\Application\Command\OpenAccountCommand;
use Chip\InterestAccount\Application\Command\Validation\ValidationError;
use Chip\InterestAccount\Application\OpenAccount;
use Chip\InterestAccount\Domain\Account\AccountStatus;
use Chip\InterestAccount\Domain\User\UUID;
use Chip\InterestAccount\Infrastructure\Repository\User\Exception\UserAlreadyHasAccount;
use Chip\InterestAccount\Tests\Support\Repository\UserSupportRepository;
use Chip\InterestAccount\Tests\Support\UserSupportFactory;
use PHPUnit\Framework\TestCase;

/**
 * Mock StatsAPI -> https://app.swaggerhub.com/apis-docs/juniormp/StatsAPI/1.0.0#/
 */
class OpenAccountFeatureTest extends TestCase
{
    private $container;
    private $subject;

    protected function setUp(): void
    {
        $this->container = require './app/bootstrap.php';
        $this->subject = $this->container->get(OpenAccount::class);
    }

    protected function tearDown(): void
    {
        UserSupportRepository::cleanUserData();
    }

    /**
     * Since the client service wants to open an interest account
     * And informed the user id (UUIDv4)
     * When entering this information through the interface
     * Then a new account must be created.
     */
    public function test_create_an_interest_account()
    {
        $id = UUID::v4();
        $income = 499999.0;
        $interestRate = 1.02;
        $statusAccount = "ACTIVE";

        $user = $this->subject->execute(new OpenAccountCommand($id));

        $this->assertEquals($id, $user["user"]["id"]);
        $this->assertEquals($income, $user["user"]["income"]["value"]);
        $this->assertEquals($id, $user["user"]["account"]["referenceId"]);
        $this->assertEquals($statusAccount, $user["user"]["account"]["status"]);
        $this->assertEquals($interestRate, $user["user"]["account"]["interestRate"]["annualRate"]);
    }

    /**
     * Since the client service wants to open an interest account
     * And informed the user id not formatted to (UUIDv4)
     * When entering this information through the interface
     * Then should validate and thrown a id format exception
     */
    public function test_should_thrown_exception_when_id_is_not_formatted()
    {
        $id = "id-not-formatted";

        $this->expectException(ValidationError::class);
        $this->expectExceptionMessage("ID SHOULD BE UUIDv4 FORMAT");

        $this->subject->execute(new OpenAccountCommand($id));
    }

    /**
     * Since the client service wants to open an interest account for a user already related to an account
     * And informed the same user id (UUIDv4)
     * When entering this information through the interface
     * Then a error message should be returned to the client
     */
    public function test_users_can_have_only_one_interest_account()
    {
        $id = UUID::v4();
        $user = UserSupportFactory::getInstance()::withId($id)::build();
        UserSupportRepository::persistUser($user);

        $this->expectException(UserAlreadyHasAccount::class);
        $this->expectExceptionMessage(UserAlreadyHasAccount::MESSAGE);

        $this->subject->execute(new OpenAccountCommand($id));
    }

    /**
     * Since the client service wants to open an interest account
     * When her income per month is not known
     * Then her yearly interest rate (per annum) is 0.5%
     */
    public function test_interest_rate_is_set_to_zero_dot_five_if_income_per_month_is_not_know()
    {
        // This id is mocked on swagger hub and always will bring the income value as 0
        $id = "aaa00000-2b32-4964-aaeb-7d3c065bc0f0";
        $income = 0;
        $interestRate = 0.5;
        $statusAccount = AccountStatus::ACTIVE;

        $user = $this->subject->execute(new OpenAccountCommand($id));

        $this->assertEquals($id, $user["user"]["id"]);
        $this->assertEquals($income, $user["user"]["income"]["value"]);
        $this->assertEquals($id, $user["user"]["account"]["referenceId"]);
        $this->assertEquals($statusAccount, $user["user"]["account"]["status"]);
        $this->assertEquals($interestRate, $user["user"]["account"]["interestRate"]["annualRate"]);
    }

    /**
     * Since the client service wants to open an interest account
     * When her income per month is less than 5000
     * Then her yearly interest rate (per annum) is 0.93%
     */
    public function test_interest_rate_is_set_to_zero_dot_ninety_three_if_income_per_month_is_less_than_five_thousand()
    {
        // This id is mocked on swagger hub and always will bring the income value as 4999
        $id = "00000000-2b32-4964-aaeb-7d3c065bc0f0";
        $income = 4999;
        $interestRate = 0.93;
        $statusAccount = AccountStatus::ACTIVE;

        $user = $this->subject->execute(new OpenAccountCommand($id));

        $this->assertEquals($id, $user["user"]["id"]);
        $this->assertEquals($income, $user["user"]["income"]["value"]);
        $this->assertEquals($id, $user["user"]["account"]["referenceId"]);
        $this->assertEquals($statusAccount, $user["user"]["account"]["status"]);
        $this->assertEquals($interestRate, $user["user"]["account"]["interestRate"]["annualRate"]);
    }

    /**
     * Since the client service wants to open an interest account
     * When her income per month is equal to 5000
     * Then her yearly interest rate (per annum) is 1.02%
     */
    public function test_interest_rate_is_set_to_one_dot_zero_two_if_income_per_month_is_equal_to_five_thousand()
    {
        // This id is mocked on swagger hub and always will bring the income value as 5000
        $id = "b5a4054d-2b32-4964-aaeb-7d3c065bc0f0";
        $income = 5000;
        $interestRate = 1.02;
        $statusAccount = AccountStatus::ACTIVE;

        $user = $this->subject->execute(new OpenAccountCommand($id));

        $this->assertEquals($id, $user["user"]["id"]);
        $this->assertEquals($income, $user["user"]["income"]["value"]);
        $this->assertEquals($id, $user["user"]["account"]["referenceId"]);
        $this->assertEquals($statusAccount, $user["user"]["account"]["status"]);
        $this->assertEquals($interestRate, $user["user"]["account"]["interestRate"]["annualRate"]);
    }

    /**
     * Since the client service wants to open an interest account
     * When her income per month is greater than 5000
     * Then her yearly interest rate (per annum) is 1.02%
     */
    public function test_interest_rate_is_set_to_one_dot_zero_two_if_income_per_month_is_greater_than_five_thousand()
    {
        // This id is mocked on swagger hub and always will bring the income value as 5100
        $id = "7f71ab26-b0a0-43ab-a8b6-bf6bc7687fe5";
        $income = 5100;
        $interestRate = 1.02;
        $statusAccount = AccountStatus::ACTIVE;

        $user = $this->subject->execute(new OpenAccountCommand($id));

        $this->assertEquals($id, $user["user"]["id"]);
        $this->assertEquals($income, $user["user"]["income"]["value"]);
        $this->assertEquals($id, $user["user"]["account"]["referenceId"]);
        $this->assertEquals($statusAccount, $user["user"]["account"]["status"]);
        $this->assertEquals($interestRate, $user["user"]["account"]["interestRate"]["annualRate"]);
    }
}

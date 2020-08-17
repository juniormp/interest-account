<?php

use Chip\InterestAccount\Application\CalculatePayout;
use Chip\InterestAccount\Application\Command\CalculatePayoutCommand;
use Chip\InterestAccount\Domain\Money\Money;
use Chip\InterestAccount\Domain\User\UUID;
use Chip\InterestAccount\Tests\Support\AccountSupportFactory;
use Chip\InterestAccount\Tests\Support\InterestRateSupportFactory;
use Chip\InterestAccount\Tests\Support\MoneySupportFactory;
use Chip\InterestAccount\Tests\Support\PayoutSupportFactory;
use Chip\InterestAccount\Tests\Support\Repository\PayoutSupportRepository;
use Chip\InterestAccount\Tests\Support\Repository\UserSupportRepository;
use Chip\InterestAccount\Tests\Support\UserSupportFactory;
use PHPUnit\Framework\TestCase;

class CalculatePayoutFeatureTest extends TestCase
{
    private $container;
    private $subject;

    protected function tearDown(): void
    {
        UserSupportRepository::cleanUserData();
        PayoutSupportRepository::cleanPayoutData();
    }

    protected function setUp(): void
    {
        $this->container = require './app/bootstrap.php';

        $this->subject = $this->container->get(CalculatePayout::class);
    }

    /**
     * Since the calculated interest is at least 1 penny
     * Then  it's deposited to the user account after calculation
     */
    public function test_should_deposit_payout_with_amount_is_greater_than_1_penny()
    {
        $id = UUID::v4();
        $interestRate = InterestRateSupportFactory::getInstance()::withRate(1.02)::build();
        $account = AccountSupportFactory::getInstance()::getInstance()::withReferenceId($id)
            ::withInterestRate($interestRate)::build();
        $amount = MoneySupportFactory::getInstance()::withAmount(50000.0)::build();
        $account->deposit($amount);
        $user = UserSupportFactory::getInstance()::withId($id)::withAccount($account)::build();

        UserSupportRepository::persistUser($user);

        $this->subject->execute(new CalculatePayoutCommand($user->getId()));

        $transactions = UserSupportRepository::getUserById($user->getId())->getTransactions();
        $this->assertCount(2, $transactions);
        $this->assertEquals(50000.0, $transactions[0]->getAmount());
        $this->assertEquals(4.2286500763221, $transactions[1]->getAmount());
    }

    /**
     * Since the calculated interest is less than 1 penny
     * Then  user deposit should be skipped.
     */
    public function test_should_skip_deposit_if_payout_is_less_than_1_penny()
    {
        $id = UUID::v4();
        $interestRate = InterestRateSupportFactory::getInstance()::withRate(1.02)::build();
        $account = AccountSupportFactory::getInstance()::withReferenceId($id)
            ::withInterestRate($interestRate)::build();
        $amount = MoneySupportFactory::getInstance()::withAmount(200.0)::build();
        $account->deposit($amount);
        $user = UserSupportFactory::getInstance()::withId($id)::withAccount($account)::build();
        UserSupportRepository::persistUser($user);

        $this->subject->execute(new CalculatePayoutCommand($user->getId()));

        $transactions = UserSupportRepository::getUserById($user->getId())->getTransactions();
        $this->assertCount(1, $transactions);
        $this->assertEquals(200, $transactions[0]->getAmount());

        $payouts = PayoutSupportRepository::getAll();
        $this->assertCount(1, $payouts);
        $this->assertEquals(0.016914600305284466, $payouts[0]->getAmount());
    }

    /**
     * Since skipped payments are stored
     * Then the calculated interest is added to the next interest payout
     */
    public function test_should_deposit_skipped_payouts_when_greater_than_1_penny()
    {
        // Save on data user with one transaction of 5000 pounds
        $id = UUID::v4();
        $interestRate = InterestRateSupportFactory::getInstance()::withRate(1.02)::build();
        $account = AccountSupportFactory::getInstance()::withReferenceId($id)
            ::withInterestRate($interestRate)::build();
        $account->deposit(new Money(5000));
        $user = UserSupportFactory::getInstance()::withId($id)::withAccount($account)::build();
        UserSupportRepository::persistUser($user);

        // Save on data previously payout that was not deposit to user account due to minimum amount needed to be deposit
        $amount = MoneySupportFactory::getInstance()::withAmount(0.99)::build();
        $payout = PayoutSupportFactory::getInstance()::withAmount($amount)::withReferenceId($id)::build();
        PayoutSupportRepository::persistPayout($payout);

        // Assert that there is only one transaction on user account
        $transactions = UserSupportRepository::getUserById($user->getId())->getTransactions();
        $this->assertCount(1, $transactions);
        $this->assertEquals(5000, $transactions[0]->getAmount());

        // Assert that there is only one payout related with user account that are pending to be deposit
        $payouts = PayoutSupportRepository::getAll();
        $this->assertCount(1, $payouts);
        $this->assertEquals(0.99, $payouts[0]->getAmount());

        $this->subject->execute(new CalculatePayoutCommand($user->getId()));

        // Now there are two transactions, one is the initial deposit and the second is the pending payout
        $transactions = UserSupportRepository::getUserById($user->getId())->getTransactions();
        $this->assertCount(2, $transactions);
        $this->assertEquals(5000, $transactions[0]->getAmount());
        $this->assertEquals(1.412865007632, $transactions[1]->getAmount());

        // Assert that there is non pending payout related with user account
        $payouts = PayoutSupportRepository::getAll();
        $this->assertCount(0, $payouts);
    }
}

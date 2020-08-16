<?php


use Chip\InterestAccount\Application\ClosePayouts;
use Chip\InterestAccount\Domain\Money\Money;
use Chip\InterestAccount\Domain\User\User;
use Chip\InterestAccount\Domain\User\UUID;
use Chip\InterestAccount\Tests\Support\AccountSupportFactory;
use Chip\InterestAccount\Tests\Support\InterestRateSupportFactory;
use Chip\InterestAccount\Tests\Support\MoneySupportFactory;
use Chip\InterestAccount\Tests\Support\PayoutSupportFactory;
use Chip\InterestAccount\Tests\Support\Repository\PayoutSupportRepository;
use Chip\InterestAccount\Tests\Support\Repository\UserSupportRepository;
use Chip\InterestAccount\Tests\Support\UserSupportFactory;
use PHPUnit\Framework\TestCase;

class ClosePayoutsFeatureTest extends TestCase
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
        $this->container = require '/Users/mauricio.junior/workspace/interest-account/app/bootstrap.php';
        $this->subject = $this->container->get(ClosePayouts::class);
    }

    public function test_should_calculate_payout_for_all_users_that_has_pending_payouts()
    {
        $user1 = $this->setUpAccount();
        $user2 = $this->setUpAccount();

        // Assert that there is only one transaction on user account
        $transactions = $user1->getTransactions();
        $this->assertCount(1, $transactions);
        $this->assertEquals(5000, $transactions[0]->getAmount());

        $transactions = $user2->getTransactions();
        $this->assertCount(1, $transactions);
        $this->assertEquals(5000, $transactions[0]->getAmount());

        // Assert that there is only one payout related with user account that are pending to be deposit
        $payouts = PayoutSupportRepository::getAllPayouts();
        $this->assertCount(2, $payouts);
        $this->assertEquals(0.99, $payouts[0]->getAmount());
        $this->assertEquals(0.99, $payouts[1]->getAmount());

        $this->subject->execute();

        $transactionsUser1 = UserSupportRepository::getUserById($user1->getId())->getTransactions();
        $transactionsUser2 = UserSupportRepository::getUserById($user2->getId())->getTransactions();

        // Now there are two transactions, one is the initial deposit and the second is the pending payout
        $this->assertCount(2, $transactionsUser1);
        $this->assertEquals(5000.0, $transactionsUser1[0]->getAmount());
        $this->assertEquals(1.4128650076320264, $transactionsUser1[1]->getAmount());

        $this->assertCount(2, $transactionsUser2);
        $this->assertEquals(5000.0, $transactionsUser2[0]->getAmount());
        $this->assertEquals(1.4128650076320264, $transactionsUser2[1]->getAmount());

        // Assert that there is non pending payout related with user account
        $payouts = PayoutSupportRepository::getAllPayouts();
        $this->assertCount(0, $payouts);
    }

    private function setUpAccount(): User
    {
        // Save on data user
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

        return $user;
    }
}

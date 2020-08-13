<?php

use Chip\InterestAccount\Application\CalculatePayout;
use Chip\InterestAccount\Application\Command\CalculatePayoutCommand;
use Chip\InterestAccount\Domain\Account\Account;
use Chip\InterestAccount\Domain\InterestRate\InterestRate;
use Chip\InterestAccount\Domain\Money\Money;
use Chip\InterestAccount\Domain\Payout\Payout;
use Chip\InterestAccount\Domain\User\User;
use Chip\InterestAccount\Domain\User\UUID;
use Chip\InterestAccount\Infrastructure\Repository\Payout\PayoutProvider;
use Chip\InterestAccount\Infrastructure\Repository\User\UserProvider;
use PHPUnit\Framework\TestCase;

class CalculatePayoutFeatureTest extends TestCase
{
    protected function tearDown(): void
    {
        UserProvider::getInstance()->destroy();
        PayoutProvider::getInstance()->destroy();
        PayoutProvider::getInstance()->cleanPayouts();
    }

    /**
     * Since the calculated interest is at least 1 penny
     * Then  it's deposited to the user account after calculation
     */
    public function test_should_deposit_payout_with_amount_is_greater_than_1_penny()
    {
        $id = UUID::v4();
        $interestRate = new InterestRate();
        $interestRate->setAnnualRate(1.02);

        $account = new Account();
        $account->setReferenceId($id);
        $account->setInterestRate($interestRate);
        $account->deposit(new Money(50000));

        $user = new User();
        $user->setAccount($account);
        $user->setId($id);

        $user = UserProvider::getInstance()->save($user);

        $container = require '/Users/mauricio.junior/workspace/interest-account/app/bootstrap.php';
        $service = $container->get(CalculatePayout::class);

        $service->execute(new CalculatePayoutCommand($user->getId()));

        $transactions = $user->getTransactions();
        $this->assertCount(2, $transactions);
        $this->assertEquals(50000, $transactions[0]->getAmount());
        $this->assertEquals(4.2286500763221, $transactions[1]->getAmount());
        // TODO adicionar validação para data no freeze
    }

    /**
     * Since the calculated interest is less than 1 penny
     * Then  user deposit should be skipped.
     */
    public function test_should_skip_deposit_if_payout_is_less_than_1_penny()
    {
        $id = UUID::v4();
        $interestRate = new InterestRate();
        $interestRate->setAnnualRate(1.02);

        $account = new Account();
        $account->setReferenceId($id);
        $account->setInterestRate($interestRate);
        $account->deposit(new Money(200));

        $user = new User();
        $user->setAccount($account);
        $user->setId($id);

        $user = UserProvider::getInstance()->save($user);

        $container = require '/Users/mauricio.junior/workspace/interest-account/app/bootstrap.php';
        $service = $container->get(CalculatePayout::class);

        $service->execute(new CalculatePayoutCommand($user->getId()));

        $transactions = $user->getTransactions();
        $this->assertCount(1, $transactions);
        $this->assertEquals(200, $transactions[0]->getAmount());

        $payouts = PayoutProvider::getAll();
        $this->assertCount(1, $payouts);
        $this->assertEquals(0.016914600305284466, $payouts[0]->getAmount());
        // TODO adicionar validação para data no freeze
    }

    /**
     * Since skipped payments are stored
     * Then the calculated interest is added to the next interest payout
     */
    public function test_should_deposit_skipped_payouts_when_greater_than_1_penny()
    {
        $id = UUID::v4();
        $interestRate = new InterestRate();
        $interestRate->setAnnualRate(1.02);

        $account = new Account();
        $account->setReferenceId($id);
        $account->setInterestRate($interestRate);
        $account->deposit(new Money(5000));

        $user = new User();
        $user->setAccount($account);
        $user->setId($id);

        $payoutAmount = 0.99;
        $payout = new Payout($user->getId(), new Money($payoutAmount));
        PayoutProvider::save($payout);

        $user = UserProvider::getInstance()->save($user);

        $container = require '/Users/mauricio.junior/workspace/interest-account/app/bootstrap.php';
        $service = $container->get(CalculatePayout::class);


        $transactions = $user->getTransactions();
        $this->assertCount(1, $transactions);
        $this->assertEquals(5000, $transactions[0]->getAmount());

        $payouts = PayoutProvider::getAll();
        $this->assertCount(1, $payouts);
        $this->assertEquals(0.99, $payouts[0]->getAmount());

        $service->execute(new CalculatePayoutCommand($user->getId()));

        $transactions = $user->getTransactions();
        $this->assertCount(2, $transactions);
        $this->assertEquals(5000, $transactions[0]->getAmount());
        $this->assertEquals(1.412865007632, $transactions[1]->getAmount());

        $payouts = PayoutProvider::getAll();
        $this->assertCount(0, $payouts);

        // TODO adicionar validação para data no freeze
    }
}

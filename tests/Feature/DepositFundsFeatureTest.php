<?php

use Chip\InterestAccount\Application\Command\DepositFundsCommand;
use Chip\InterestAccount\Application\Command\Validation\ValidationError;
use Chip\InterestAccount\Application\DepositFunds;
use Chip\InterestAccount\Domain\User\UUID;
use Chip\InterestAccount\Tests\Support\AccountSupportFactory;
use Chip\InterestAccount\Tests\Support\MoneySupportFactory;
use Chip\InterestAccount\Tests\Support\Repository\UserSupportRepository;
use Chip\InterestAccount\Tests\Support\UserSupportFactory;
use PHPUnit\Framework\TestCase;

class DepositFundsFeatureTest extends TestCase
{
    private $container;
    private $subject;

    protected function setUp(): void
    {
        $this->container = require '/Users/mauricio.junior/workspace/interest-account/app/bootstrap.php';
        $this->subject = $this->container->get(DepositFunds::class);
    }

    protected function tearDown(): void
    {
        UserSupportRepository::cleanUserData();
    }

    /**
     * Since the client service wants to deposit funds
     * And informed amount to be deposit
     * When entering this information through the interface
     * Then should validate if it is her account and add funds after success validation
     */
    public function test_should_deposit_amount_in_user_account()
    {
        $id = UUID::v4();
        $account = AccountSupportFactory::getInstance()::build();
        $user = UserSupportFactory::getInstance()::withId($id)::withAccount($account)::build();
        UserSupportRepository::persistUser($user);

        $amount = 500.00;
        $transaction = $this->subject->execute(new DepositFundsCommand($id, $amount));
        $money = MoneySupportFactory::getInstance()::withAmount($amount)::build();
        $this->assertEquals($money, $transaction->getCurrency());

        $amount2 = 250.00;
        $transaction2 = $this->subject->execute(new DepositFundsCommand($id, $amount2));
        $money2 = MoneySupportFactory::getInstance()::withAmount($amount2)::build();
        $this->assertEquals($money2, $transaction2->getCurrency());

        $user = UserSupportRepository::getUserById($id);
        $transactions = $user->getTransactions();

        $this->assertCount(2, $transactions);
        $this->assertEquals($money, $transactions[0]->getCurrency());
        $this->assertEquals($money2, $transactions[1]->getCurrency());
    }

    /**
     * Since the client service wants to deposit funds
     * And informed amount to be deposit
     * When entering a negative amount through the interface
     * Then should validate and thrown a negative amount exception
     */
    public function test_should_thrown_exception_when_entering_a_negative_amount()
    {
        $id = UUID::v4();
        $account = AccountSupportFactory::getInstance()::build();
        $user = UserSupportFactory::getInstance()::withId($id)::withAccount($account)::build();
        UserSupportRepository::persistUser($user);

        $amount = -100.00;

        $this->expectException(ValidationError::class);
        $this->expectExceptionMessage("AMOUNT CAN NOT BE NEGATIVE");

        $this->subject->execute(new DepositFundsCommand($id, $amount));
    }
}

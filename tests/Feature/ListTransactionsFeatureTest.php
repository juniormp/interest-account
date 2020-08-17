<?php

use Chip\InterestAccount\Application\Command\ListTransactionsCommand;
use Chip\InterestAccount\Application\ListTransactions;
use Chip\InterestAccount\Domain\User\UUID;
use Chip\InterestAccount\Tests\Support\AccountSupportFactory;
use Chip\InterestAccount\Tests\Support\MoneySupportFactory;
use Chip\InterestAccount\Tests\Support\Repository\UserSupportRepository;
use Chip\InterestAccount\Tests\Support\UserSupportFactory;
use PHPUnit\Framework\TestCase;

class ListTransactionsFeatureTest extends TestCase
{

    private $container;
    private $subject;

    protected function setUp(): void
    {
        $this->container = require './app/bootstrap.php';
        $this->subject = $this->container->get(ListTransactions::class);
    }

    protected function tearDown(): void
    {
        UserSupportRepository::cleanUserData();
    }

    /**
     * Since the client service wants to list all account transactions
     * And informed the user id (UUIDv4)
     * When entering this information through the interface
     * Then a list of transactions should be returned
     */
    public function test_should_list_all_transactions_from_a_user_account()
    {
        $id = UUID::v4();
        $account = AccountSupportFactory::getInstance()::build();
        $user = UserSupportFactory::getInstance()::withId($id)::withAccount($account)::build();

        $amount1 = MoneySupportFactory::getInstance()::withAmount(500.00)::build();
        $account->deposit($amount1);

        $amount2 = MoneySupportFactory::getInstance()::withAmount(1000.00)::build();
        $account->deposit($amount2);

        $amount3 = MoneySupportFactory::getInstance()::withAmount(2000.00)::build();
        $account->deposit($amount3);

        UserSupportRepository::persistUser($user);

        $transactions = $this->subject->execute(new ListTransactionsCommand($id));

        $this->assertCount(3, $transactions);
        $this->assertEquals($amount1, $transactions[0]->getCurrency());
        $this->assertEquals($amount2, $transactions[1]->getCurrency());
        $this->assertEquals($amount3, $transactions[2]->getCurrency());
    }
}

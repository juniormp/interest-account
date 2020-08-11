<?php

use Chip\InterestAccount\Application\Command\ListTransactionsCommand;
use Chip\InterestAccount\Application\ListTransactions;
use Chip\InterestAccount\Domain\Account\Account;
use Chip\InterestAccount\Domain\Money\CurrencyType;
use Chip\InterestAccount\Domain\Money\MoneyFactory;
use Chip\InterestAccount\Domain\User\User;
use Chip\InterestAccount\Domain\User\UUID;
use Chip\InterestAccount\Infrastructure\Repository\User\UserProvider;
use PHPUnit\Framework\TestCase;

class ListTransactionsFeatureTest extends TestCase
{
    protected function tearDown(): void
    {
        UserProvider::getInstance()->destroy();
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
        $user = new User();
        $account = new Account();
        $moneyFactory = new MoneyFactory();
        $amount = 500.00;
        $money = $moneyFactory->create($amount, CurrencyType::GBP);
        $account->deposit($money);
        $user->setAccount($account);
        $user->setId($id);

        UserProvider::getInstance()->save($user);

        $container = require '/Users/mauricio.junior/workspace/interest-account/app/bootstrap.php';
        $service = $container->get(ListTransactions::class);

        $transactions = $service->execute(new ListTransactionsCommand($id));

        $this->assertEquals($amount, $transactions[0]->getAmount());
    }
}

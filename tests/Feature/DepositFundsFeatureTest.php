<?php

use Chip\InterestAccount\Application\Command\DepositFundsCommand;
use Chip\InterestAccount\Application\DepositFunds;
use Chip\InterestAccount\Domain\Account\Account;
use Chip\InterestAccount\Domain\Money\CurrencyType;
use Chip\InterestAccount\Domain\Money\MoneyFactory;
use Chip\InterestAccount\Domain\User\User;
use Chip\InterestAccount\Domain\User\UUID;
use Chip\InterestAccount\Infrastructure\Repository\User\UserProvider;
use PHPUnit\Framework\TestCase;

class DepositFundsFeatureTest extends TestCase
{
    protected function tearDown(): void
    {
        UserProvider::getInstance()->destroy();
    }

    /**
     * Since the client service wants to deposit funds
     * And informed amount to be deposit
     * When entering this information through the interface
     * Then should validate if it is her account and add funds after success validation
     */
    public function test_should_deposit_amount_in_user_account()
    {
        $user = new User();
        $user->setAccount(new Account());
        $id = UUID::v4();
        $user->setId($id);
        UserProvider::getInstance()->save($user);

        $container = require '/Users/mauricio.junior/workspace/interest-account/app/bootstrap.php';
        $service = $container->get(DepositFunds::class);

        $moneyFactory = new MoneyFactory();

        $amount = 500.00;
        $money = $moneyFactory->create($amount, CurrencyType::GBP);
        $transaction = $service->execute(new DepositFundsCommand($id, $amount));
        $this->assertEquals($money, $transaction->getAmount());

        $amount2 = 250.00;
        $money2 = $moneyFactory->create($amount2, CurrencyType::GBP);
        $transaction2 = $service->execute(new DepositFundsCommand($id, $amount2));
        $this->assertEquals($money2, $transaction2->getAmount());

        $user = UserProvider::getInstance()->findById($id);
        $transactions = $user->getTransactions();
        $this->assertCount(2, $transactions);
    }
}

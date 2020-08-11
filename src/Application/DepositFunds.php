<?php


namespace Chip\InterestAccount\Application;

use Chip\InterestAccount\Application\Command\DepositFundsCommand;
use Chip\InterestAccount\Domain\Money\CurrencyType;
use Chip\InterestAccount\Domain\Money\MoneyFactory;
use Chip\InterestAccount\Domain\Transaction\Transaction;
use Chip\InterestAccount\Infrastructure\Repository\User\UserProvider;

class DepositFunds
{
    private $userProvider;

    public function __construct(UserProvider $userProvider)
    {
        $this->userProvider = $userProvider;
    }

    public function execute(DepositFundsCommand $depositFundsCommand): Transaction
    {
        $user = $this->userProvider->findById($depositFundsCommand->getId());
        $account = $user->getAccount();

        $moneyFactory = new MoneyFactory();
        $amount = $moneyFactory->create($depositFundsCommand->getAmount(), CurrencyType::GBP);

        return $account->deposit($amount);
    }
}

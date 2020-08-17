<?php


namespace Chip\InterestAccount\Application;

use Chip\InterestAccount\Application\Command\DepositFundsCommand;
use Chip\InterestAccount\Domain\Money\CurrencyType;
use Chip\InterestAccount\Domain\Money\MoneyFactory;
use Chip\InterestAccount\Domain\Transaction\Transaction;
use Chip\InterestAccount\Infrastructure\Repository\User\UserRepository;

class DepositFunds
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(DepositFundsCommand $depositFundsCommand): Transaction
    {
        $user = $this->userRepository->findById($depositFundsCommand->getId());
        $account = $user->getAccount();

        $moneyFactory = new MoneyFactory();
        $amount = $moneyFactory->create($depositFundsCommand->getAmount(), CurrencyType::GBP);

        $transaction = $account->deposit($amount);

        $this->userRepository->update($user);

        return $transaction;
    }
}

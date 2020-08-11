<?php

namespace Chip\InterestAccount\Application;

use Chip\InterestAccount\Application\Command\OpenAccountCommand;
use Chip\InterestAccount\Domain\Account\Account;
use Chip\InterestAccount\Domain\Account\AccountStatus;
use Chip\InterestAccount\Domain\InterestRate\ApplyInterestRateService;
use Chip\InterestAccount\Domain\Money\Money;
use Chip\InterestAccount\Domain\User\User;
use Chip\InterestAccount\Domain\User\UserFactory;
use Chip\InterestAccount\Infrastructure\ExternalData\StatsAPI\GetUserIncomeService;
use Chip\InterestAccount\Infrastructure\Repository\User\UserProvider;

class OpenAccount
{
    private $applyInterestRateService;
    private $userFactory;
    private $userProvider;
    private $getUserIncomeService;

    public function __construct(
        ApplyInterestRateService $applyInterestRateService,
        UserFactory $userFactory,
        UserProvider $userProvider,
        GetUserIncomeService $getUserIncomeService
    ) {
        $this->applyInterestRateService = $applyInterestRateService;
        $this->userFactory = $userFactory;
        $this->userProvider = $userProvider;
        $this->getUserIncomeService = $getUserIncomeService;
    }

    public function execute(OpenAccountCommand $command): User
    {
        $user = $this->createUser($command);
        $user = $this->setUserIncome($user);
        $user = $this->applyInterestRateService->apply($user);

        return $this->userProvider->save($user);
    }

    private function createUser(OpenAccountCommand $command): User
    {
        $income = new Money();
        $account = new Account();
        $account->setStatus(AccountStatus::ACTIVE);

        return $this->userFactory->create($command->getId(), $income, $account);
    }

    private function setUserIncome(User $user)
    {
        $incomeValue = $this->getUserIncomeService->getIncome($user->getId());
        $income = $user->getIncome();
        $income->setValue($incomeValue);

        return $user;
    }
}

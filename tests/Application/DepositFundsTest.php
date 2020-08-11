<?php


use Chip\InterestAccount\Application\Command\OpenAccountCommand;
use Chip\InterestAccount\Application\OpenAccount;
use Chip\InterestAccount\Domain\Account\Account;
use Chip\InterestAccount\Domain\Account\AccountStatus;
use Chip\InterestAccount\Domain\InterestRate\ApplyInterestRateService;
use Chip\InterestAccount\Domain\Money\Money;
use Chip\InterestAccount\Domain\User\User;
use Chip\InterestAccount\Domain\User\UserFactory;
use Chip\InterestAccount\Infrastructure\ExternalData\StatsAPI\GetUserIncomeService;
use Chip\InterestAccount\Infrastructure\Repository\User\UserProvider;
use PHPUnit\Framework\TestCase;

class OpenAccountTest extends TestCase
{
    public function test_should_open_an_interest_account()
    {
        $applyInterestRateService = $this->createMock(ApplyInterestRateService::class);
        $userFactory = $this->createMock(UserFactory::class);
        $userProvider = $this->createMock(UserProvider::class);
        $getUserIncomeService = $this->createMock(GetUserIncomeService::class);
        $user = $this->createMock(User::class);
        $account = (new Account())->setStatus(AccountStatus::ACTIVE);
        $money = new Money();
        $id = "7f71ab26-b0a0-43ab-a8b6-bf6bc7687fe5";
        $income = 5000.0;

        $subject = new OpenAccount($applyInterestRateService, $userFactory, $userProvider, $getUserIncomeService);

        $userFactory
            ->method('create')
            ->with($id, $money, $account)
            ->willReturn($user);
        $applyInterestRateService
            ->method('apply')
            ->with($user)
            ->willReturn($user);
        $user->method('getId')->willReturn($id);
        $getUserIncomeService->method('getIncome')->with($id)->willReturn($income);
        $applyInterestRateService->method('apply')->with($user)->willReturn($user);
        $userProvider->method('save')->with($user)->willReturn($user);

        $result = $subject->execute(new OpenAccountCommand($id));

        $this->assertEquals($user, $result);

        Mockery::close();
    }
}

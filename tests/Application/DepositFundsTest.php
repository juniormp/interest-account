<?php


use Chip\InterestAccount\Application\Command\DepositFundsCommand;
use Chip\InterestAccount\Application\DepositFunds;
use Chip\InterestAccount\Domain\Account\Account;
use Chip\InterestAccount\Domain\Money\CurrencyType;
use Chip\InterestAccount\Domain\User\User;
use Chip\InterestAccount\Infrastructure\Repository\User\UserProvider;
use PHPUnit\Framework\TestCase;

class DepositFundsTest extends TestCase
{
    public function test_should_deposit_funds_to_an_account()
    {
        $userProvider = $this->createMock(UserProvider::class);
        $subject = new DepositFunds($userProvider);
        $id = "00000000-2b32-4964-aaeb-7d3c065bc0f0";
        $amount = 500.00;
        $user = new User();
        $user->setAccount(new Account());
        $userProvider->method('findById')->with($id)->willReturn($user);

        $result = $subject->execute(new DepositFundsCommand($id, $amount));

        $this->assertEquals($amount, $result->getAmount()->getValue());
        $this->assertEquals(CurrencyType::GBP, $result->getAmount()->getCurrencyType());
    }
}

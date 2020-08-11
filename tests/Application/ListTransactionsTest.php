<?php


use Chip\InterestAccount\Application\Command\ListTransactionsCommand;
use Chip\InterestAccount\Application\ListTransactions;
use Chip\InterestAccount\Domain\Transaction\Transaction;
use Chip\InterestAccount\Domain\User\User;
use Chip\InterestAccount\Infrastructure\Repository\User\UserProvider;
use PHPUnit\Framework\TestCase;

class ListTransactionsTest extends TestCase
{
    public function test_should_list_transactions_from_user_account()
    {
        $userProvider = $this->createMock(UserProvider::class);
        $subject = new ListTransactions($userProvider);
        $id = "00000000-2b32-4964-aaeb-7d3c065bc0f0";
        $user = $this->createMock(User::class);
        $transaction = $this->createMock(Transaction::class);
        $userProvider->method('findById')->with($id)->willReturn($user);
        $user->method('getTransactions')->willReturn([$transaction]);

        $result = $subject->execute(new ListTransactionsCommand($id));

        $this->assertIsArray($result);
        $this->assertEquals($transaction, $result[0]);
    }
}

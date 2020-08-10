<?php

use Chip\InterestAccount\Domain\Account\Account;
use Chip\InterestAccount\Domain\Money\Money;
use Chip\InterestAccount\Domain\User\User;
use Chip\InterestAccount\Domain\User\UUID;
use Chip\InterestAccount\Infrastructure\Repository\User\UserProvider;
use PHPUnit\Framework\TestCase;

class UserProviderTest extends TestCase
{
    private $subject;

    protected function setUp(): void
    {
        $this->subject = UserProvider::getInstance();
    }

    protected function tearDown(): void
    {
        $this->subject->destroy();
    }

    public function test_should_return_the_same_provider_instance()
    {
        $firstUserProvider = UserProvider::getInstance();
        $secondUserProvider = UserProvider::getInstance();

        $this->assertSame($firstUserProvider, $secondUserProvider);
    }

    public function test_should_save_user()
    {
        $user = new User();
        $account = new Account();
        $income = new Money();
        $id = UUID::v4();
        $user->setId($id)->setAccount($account)->setIncome($income);

        $this->subject->save($user);

        $this->assertEquals(1, $this->subject->count());
        $savedUser = $this->subject->offsetGet($user);

        $this->assertSame($account, $savedUser->getAccount());
        $this->assertSame($income, $savedUser->getIncome());
        $this->assertSame($id, $savedUser->getId());
    }

    public function test_should_return_updated_user_after_saving()
    {
        $user = new User();
        $account = new Account();
        $income = new Money();
        $id = UUID::v4();
        $user->setId($id)->setAccount($account)->setIncome($income);

        $result = $this->subject->save($user);

        $this->assertEquals(1, $this->subject->count());
        $this->assertSame($account, $result->getAccount());
        $this->assertSame($income, $result->getIncome());
        $this->assertSame($id, $user->getId());
    }

    public function test_should_find_user_by_id()
    {
        $user = new User();
        $id = UUID::v4();
        $user->setId($id);
        $this->subject->save($user);

        $result = $this->subject->findById($id);

        $this->assertSame($id, $result->getId());
    }
}

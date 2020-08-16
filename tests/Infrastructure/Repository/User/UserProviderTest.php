<?php

use Chip\InterestAccount\Domain\User\User;
use Chip\InterestAccount\Domain\User\UUID;
use Chip\InterestAccount\Infrastructure\Repository\User\Exception\UserAlreadyHasAccount;
use Chip\InterestAccount\Infrastructure\Repository\User\Exception\UserNotFoundException;
use Chip\InterestAccount\Infrastructure\Repository\User\UserProvider;
use Chip\InterestAccount\Tests\Support\AccountSupportFactory;
use Chip\InterestAccount\Tests\Support\MoneySupportFactory;
use Chip\InterestAccount\Tests\Support\UserSupportFactory;
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
        $id = UUID::v4();
        $income = MoneySupportFactory::getInstance()::build();
        $account = AccountSupportFactory::getInstance()::build();
        $user = UserSupportFactory::getInstance()::withId($id)::withAccount($account)::withIncome($income)::build();

        $this->subject->save($user);

        $this->assertEquals(1, $this->subject->count());
        $savedUser = $this->subject->offsetGet($user);
        $this->assertSame($account, $savedUser->getAccount());
        $this->assertSame($income, $savedUser->getIncome());
        $this->assertSame($id, $savedUser->getId());
    }

    public function test_should_return_updated_user_after_saving()
    {
        $id = UUID::v4();
        $income = MoneySupportFactory::getInstance()::build();
        $account = AccountSupportFactory::getInstance()::build();
        $user = UserSupportFactory::getInstance()::withId($id)::withAccount($account)::withIncome($income)::build();

        $result = $this->subject->save($user);

        $this->assertEquals(1, $this->subject->count());
        $this->assertSame($account, $result->getAccount());
        $this->assertSame($income, $result->getIncome());
        $this->assertSame($id, $user->getId());
    }

    public function test_should_find_user_by_id()
    {
        $id = UUID::v4();
        $user = UserSupportFactory::getInstance()::withId($id)::build();
        $this->subject->save($user);

        $result = $this->subject->findById($id);

        $this->assertSame($id, $result->getId());
    }

    public function test_should_throw_exception_when_saving_if_user_has_already_an_account()
    {
        $id = UUID::v4();
        $income = MoneySupportFactory::getInstance()::build();
        $account = AccountSupportFactory::getInstance()::build();
        $user = UserSupportFactory::getInstance()::withId($id)::withAccount($account)::withIncome($income)::build();
        UserProvider::getInstance()->save($user);

        $user = new User();
        $user->setId($id)->setAccount($account)->setIncome($income);

        $this->expectException(UserAlreadyHasAccount::class);
        $this->expectExceptionMessage(UserAlreadyHasAccount::MESSAGE);

        $this->subject->save($user);
    }

    public function test_should_throw_exception_when_finding_if_user_do_not_exist()
    {
        $id = UUID::v4();

        $this->expectException(UserNotFoundException::class);
        $this->expectExceptionMessage(UserNotFoundException::MESSAGE);

        $this->subject->findById($id);
    }

    public function test_should_return_all_users_id()
    {
        $id1 = UUID::v4();
        $user = UserSupportFactory::getInstance()::withId($id1)::build();
        UserProvider::getInstance()->save($user);

        $id2 = UUID::v4();
        $user = UserSupportFactory::getInstance()::withId($id2)::build();
        UserProvider::getInstance()->save($user);

        $id3 = UUID::v4();
        $user = UserSupportFactory::getInstance()::withId($id3)::build();
        UserProvider::getInstance()->save($user);

        $result = $this->subject->findAllIds();

        $this->assertCount(3, $result);
        $this->assertEquals($id1, $result[0]);
        $this->assertEquals($id2, $result[1]);
        $this->assertEquals($id3, $result[2]);
    }
}

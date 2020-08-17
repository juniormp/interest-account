<?php

use Chip\InterestAccount\Domain\User\User;
use Chip\InterestAccount\Domain\User\UUID;
use Chip\InterestAccount\Infrastructure\Repository\User\Exception\UserAlreadyHasAccount;
use Chip\InterestAccount\Infrastructure\Repository\User\Exception\UserNotFoundException;
use Chip\InterestAccount\Infrastructure\Repository\User\UserProvider;
use Chip\InterestAccount\Tests\Support\AccountSupportFactory;
use Chip\InterestAccount\Tests\Support\MoneySupportFactory;
use Chip\InterestAccount\Tests\Support\Repository\UserSupportRepository;
use Chip\InterestAccount\Tests\Support\UserSupportFactory;
use PHPUnit\Framework\TestCase;

class UserProviderTest extends TestCase
{
    private $subject;

    protected function setUp(): void
    {
        $this->subject = new UserProvider();
    }

    protected function tearDown(): void
    {
        UserSupportRepository::cleanUserData();
    }

    /**
     * @covers ::save
     */
    public function test_should_save_user()
    {
        $id = UUID::v4();
        $income = MoneySupportFactory::getInstance()::build();
        $account = AccountSupportFactory::getInstance()::build();
        $user = UserSupportFactory::getInstance()::withId($id)::withAccount($account)::withIncome($income)::build();

        $this->subject->save($user);

        $userSaved = UserSupportRepository::getUserById($id);
        $this->assertEquals($user, $userSaved);
    }

    /**
     * @covers ::save
     */
    public function test_should_return_saved_user_after_saving()
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

    /**
     * @covers ::save
     */
    public function test_should_throw_exception_when_saving_if_user_has_already_an_account()
    {
        $id = UUID::v4();
        $income = MoneySupportFactory::getInstance()::build();
        $account = AccountSupportFactory::getInstance()::build();
        $user = UserSupportFactory::getInstance()::withId($id)::withAccount($account)::withIncome($income)::build();
        UserSupportRepository::persistUser($user);

        $user = new User();
        $user->setId($id)->setAccount($account)->setIncome($income);

        $this->expectException(UserAlreadyHasAccount::class);
        $this->expectExceptionMessage(UserAlreadyHasAccount::MESSAGE);

        $this->subject->save($user);
    }

    /**
     * @covers ::update
     */
    public function test_should_update_user()
    {
        $id = UUID::v4();
        $user = UserSupportFactory::getInstance()::withId($id)::build();
        UserSupportRepository::persistUser($user);
        $account = AccountSupportFactory::getInstance()::build();

        $userPersisted = UserSupportRepository::getUserById($user->getId());
        $userPersisted->setAccount($account);
        $this->subject->update($userPersisted);

        $userUpdated = UserSupportRepository::getUserById($userPersisted->getId());
        $this->assertEquals($userPersisted, $userUpdated);
    }

    /**
     * @covers ::update
     */
    public function test_should_return_updated_user_after_saving()
    {
        $id = UUID::v4();
        $user = UserSupportFactory::getInstance()::withId($id)::build();
        UserSupportRepository::persistUser($user);
        $account = AccountSupportFactory::getInstance()::build();

        $userPersisted = UserSupportRepository::getUserById($user->getId());
        $userPersisted->setAccount($account);
        $result = $this->subject->update($userPersisted);

        $this->assertEquals(1, $this->subject->count());
        $this->assertSame($account, $result->getAccount());
        $this->assertSame($id, $user->getId());
    }

    /**
     * @covers ::save
     */
    public function test_should_throw_exception_when_updating_if_user_do_not_exist()
    {
        $id = UUID::v4();
        $user = UserSupportFactory::getInstance()::withId($id)::build();
        UserSupportRepository::persistUser($user);

        $userNotPersisted = UserSupportFactory::getInstance()::withId("fake-id")::build();

        $this->expectException(UserNotFoundException::class);
        $this->expectExceptionMessage(UserNotFoundException::MESSAGE);

        $this->subject->update($userNotPersisted);
    }

    /**
     * @covers ::findById
     */
    public function test_should_find_user_by_id()
    {
        $id = UUID::v4();
        $user = UserSupportFactory::getInstance()::withId($id)::build();
        UserSupportRepository::persistUser($user);

        $result = $this->subject->findById($id);

        $this->assertSame($id, $result->getId());
    }

    /**
     * @covers ::findById
     */
    public function test_should_throw_exception_when_finding_if_user_do_not_exist()
    {
        $id = UUID::v4();

        $this->expectException(UserNotFoundException::class);
        $this->expectExceptionMessage(UserNotFoundException::MESSAGE);

        $this->subject->findById($id);
    }

    /**
     * @covers ::findAllIds
     */
    public function test_should_return_all_users_id()
    {
        $id1 = UUID::v4();
        $user = UserSupportFactory::getInstance()::withId($id1)::build();
        UserSupportRepository::persistUser($user);

        $id2 = UUID::v4();
        $user = UserSupportFactory::getInstance()::withId($id2)::build();
        UserSupportRepository::persistUser($user);

        $id3 = UUID::v4();
        $user = UserSupportFactory::getInstance()::withId($id3)::build();
        UserSupportRepository::persistUser($user);

        $result = $this->subject->findAllIds();

        $this->assertCount(3, $result);
        $this->assertEquals($id1, $result[0]);
        $this->assertEquals($id2, $result[1]);
        $this->assertEquals($id3, $result[2]);
    }
}

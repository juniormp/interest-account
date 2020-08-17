<?php

namespace Chip\InterestAccount\Tests\Support\Repository;

use Chip\InterestAccount\Domain\User\User;
use Chip\InterestAccount\Infrastructure\Repository\User\UserProvider;

class UserSupportRepository
{
    private static $userProvider;

    public static function cleanUserData(): void
    {
        file_put_contents("user_repo.txt", "");
    }

    public static function persistUser(User $user): void
    {
        self::$userProvider = new UserProvider();
        self::$userProvider->save($user);
    }

    public static function getUserById(string $id): User
    {
        self::$userProvider = new UserProvider();
        return self::$userProvider->findById($id);
    }
}

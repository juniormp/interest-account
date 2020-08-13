<?php

namespace Chip\InterestAccount\Tests\Support\Repository;

use Chip\InterestAccount\Domain\User\User;
use Chip\InterestAccount\Infrastructure\Repository\User\UserProvider;

class UserSupportRepository
{
    public static function cleanUserData(): void
    {
        UserProvider::getInstance()->destroy();
    }

    public static function persistUser(User $user): void
    {
        UserProvider::getInstance()->save($user);
    }
}

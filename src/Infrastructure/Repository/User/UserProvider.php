<?php

namespace Chip\InterestAccount\Infrastructure\Repository\User;

use Chip\InterestAccount\Domain\User\User;
use Exception;
use SplObjectStorage;

class UserProvider extends SPLObjectStorage implements UserRepository
{
    private static $instances = [];

    public function getHash($user)
    {
        return $user->getId();
    }

    protected function __construct()
    {
    }

    protected function __clone()
    {
    }

    public function destroy()
    {
        self::$instances = null;
    }

    public function __wakeup()
    {
        throw new Exception("Cannot unserialize a singleton.");
    }

    public static function getInstance(): UserProvider
    {
        $cls = static::class;

        if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = new static;
        }

        return self::$instances[$cls];
    }

    public function save(User $user): User
    {
        self::attach($user, $user);

        return $user;
    }

    public function findById(string $id): User
    {
        return self::offsetGet((new User())->setId($id));
    }
}

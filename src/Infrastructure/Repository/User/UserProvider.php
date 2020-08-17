<?php

namespace Chip\InterestAccount\Infrastructure\Repository\User;

use Chip\InterestAccount\Domain\User\User;
use Chip\InterestAccount\Infrastructure\Repository\User\Exception\Repo;
use Chip\InterestAccount\Infrastructure\Repository\User\Exception\UserAlreadyHasAccount;
use Chip\InterestAccount\Infrastructure\Repository\User\Exception\UserNotFoundException;
use SplObjectStorage;

class UserProvider extends SPLObjectStorage implements UserRepository
{
    use Repo;

    public function getHash($user)
    {
        return $user->getId();
    }

    public function save(User $user): User
    {
        try {
            $this->findById($user->getId());
            throw new UserAlreadyHasAccount(UserAlreadyHasAccount::MESSAGE);
        } catch (UserNotFoundException $e) {
            $this->attach($user, $user);
            $this->saveOnFile($this);
            return $user;
        }
    }

    public function findById(string $id): User
    {
        $r = $this->readFromFile();

        if ($r !== false) {
            $this->addAll($r);
        }

        if ($this->contains((new User())->setId($id))) {
            return $this->offsetGet((new User())->setId($id));
        } else {
            throw new UserNotFoundException(UserNotFoundException::MESSAGE);
        }
    }

    public function findAllIds(): array
    {
        $r = $this->readFromFile();

        if ($r !== false) {
            $this->addAll($r);
        }

        $ids = [];
        while ($this->valid()) {
            $user = $this->current();
            array_push($ids, $user->getId());
            $this->next();
        }

        return $ids;
    }
}

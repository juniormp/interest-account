<?php

namespace Chip\InterestAccount\Infrastructure\Repository\User;

use Chip\InterestAccount\Domain\User\User;

interface UserRepository
{
    public function save(User $user): User;

    public function update(User $user): User;

    public function findById(string $id): User;

    public function findAllIds(): array;
}

<?php


namespace Chip\InterestAccount\Domain\InterestRate\Policy;

use Chip\InterestAccount\Domain\User\User;

interface Policy
{
    public function apply(User $user);
}

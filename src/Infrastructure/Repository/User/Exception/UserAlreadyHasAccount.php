<?php


namespace Chip\InterestAccount\Infrastructure\Repository\User\Exception;

use Exception;

class UserAlreadyHasAccount extends Exception
{
    public const MESSAGE = "USER ALREADY HAS AN INTEREST ACCOUNT";
}

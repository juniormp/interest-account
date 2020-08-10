<?php


namespace Chip\InterestAccount\Infrastructure\Repository\User\Exception;

use Exception;

class UserNotFoundException extends Exception
{
    public const MESSAGE = "USER NOT FOUND";
}

<?php


namespace Chip\InterestAccount\Application\Command\Validation;

use Exception;

class ValidationError extends Exception
{
    public const ID_FORMAT = "ID SHOULD BE UUIDv4 FORMAT";
}

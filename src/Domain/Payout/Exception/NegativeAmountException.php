<?php

namespace Chip\InterestAccount\Domain\Payout\Exception;

use Exception;

class NegativeAmountException extends Exception
{
    public const MESSAGE = "AMOUNT CAN NOT BE NEGATIVE";
}

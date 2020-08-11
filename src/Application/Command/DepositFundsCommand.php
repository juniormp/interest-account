<?php


namespace Chip\InterestAccount\Application\Command;

use Chip\InterestAccount\Application\Command\Validation\DepositFundsCommandValidation;

class DepositFundsCommand
{
    private $id;
    private $amount;

    public function __construct(string $id, float $amount)
    {
        DepositFundsCommandValidation::validate($id, $amount);
        $this->id = $id;
        $this->amount = $amount;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }
}

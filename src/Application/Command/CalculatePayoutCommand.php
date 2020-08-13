<?php


namespace Chip\InterestAccount\Application\Command;

use Chip\InterestAccount\Application\Command\Validation\CalculatePayoutCommandValidation;

class CalculatePayoutCommand
{
    private $id;

    public function __construct(string $id)
    {
        CalculatePayoutCommandValidation::validate($id);
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }
}

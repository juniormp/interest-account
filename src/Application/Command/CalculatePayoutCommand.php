<?php


namespace Chip\InterestAccount\Application\Command;

use Chip\InterestAccount\Application\Command\Validation\OpenAccountCommandValidation;

class CalculatePayoutCommand
{
    private $id;

    public function __construct(string $id)
    {
        OpenAccountCommandValidation::validate($id);
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }
}

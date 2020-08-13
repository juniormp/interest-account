<?php

namespace Chip\InterestAccount\Application\Command;

use Chip\InterestAccount\Application\Command\Validation\ListTransactionsCommandValidation;

class ListTransactionsCommand
{
    private $id;

    public function __construct(string $id)
    {
        ListTransactionsCommandValidation::validate($id);
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }
}

<?php


namespace Chip\InterestAccount\Application;

use Chip\InterestAccount\Application\Command\ListTransactionsCommand;
use Chip\InterestAccount\Infrastructure\Repository\User\UserProvider;

class ListTransactions
{
    private $userProvider;

    public function __construct(UserProvider $userProvider)
    {
        $this->userProvider = $userProvider;
    }

    public function execute(ListTransactionsCommand $listTransactionsCommand): array
    {
        $user = $this->userProvider->findById($listTransactionsCommand->getId());

        return $user->getTransactions();
    }
}

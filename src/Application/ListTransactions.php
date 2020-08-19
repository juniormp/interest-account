<?php


namespace Chip\InterestAccount\Application;

use Chip\InterestAccount\Application\Command\ListTransactionsCommand;
use Chip\InterestAccount\Application\Response\TransactionsResponse;
use Chip\InterestAccount\Infrastructure\Repository\User\UserRepository;

class ListTransactions
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(ListTransactionsCommand $listTransactionsCommand): array
    {
        $user = $this->userRepository->findById($listTransactionsCommand->getId());

        return TransactionsResponse::toJson($user->getTransactions());
    }
}

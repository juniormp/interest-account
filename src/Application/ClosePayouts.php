<?php


namespace Chip\InterestAccount\Application;

use Chip\InterestAccount\Application\Command\CalculatePayoutCommand;
use Chip\InterestAccount\Infrastructure\Repository\User\UserRepository;

class ClosePayouts
{
    private $userRepository;
    private $calculatePayout;

    public function __construct(UserRepository $userRepository, CalculatePayout $calculatePayout)
    {
        $this->userRepository = $userRepository;
        $this->calculatePayout = $calculatePayout;
    }

    public function execute()
    {
        $userIds = $this->userRepository->findAllIds();

        foreach ($userIds as $id) {
            $this->calculatePayout->execute(new CalculatePayoutCommand($id));
        }
    }
}

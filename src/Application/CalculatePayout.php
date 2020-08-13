<?php


namespace Chip\InterestAccount\Application;

use Chip\InterestAccount\Application\Command\CalculatePayoutCommand;
use Chip\InterestAccount\Domain\Money\CurrencyType;
use Chip\InterestAccount\Domain\Money\Money;
use Chip\InterestAccount\Domain\Payout\InterestRatePayoutService;
use Chip\InterestAccount\Infrastructure\Repository\Payout\PayoutRepository;
use Chip\InterestAccount\Infrastructure\Repository\User\UserRepository;

class CalculatePayout
{
    private $interestRatePayoutService;
    private $userRepository;
    private $payoutRepository;

    public function __construct(
        InterestRatePayoutService $interestRatePayoutService,
        UserRepository $userRepository,
        PayoutRepository $payoutRepository
    )
    {
        $this->interestRatePayoutService = $interestRatePayoutService;
        $this->userRepository = $userRepository;
        $this->payoutRepository = $payoutRepository;
    }

    public function execute(CalculatePayoutCommand $calculatePayoutCommand)
    {
        $user = $this->userRepository->findById($calculatePayoutCommand->getId());

        $interestRate = $user->getInterestRateEntity();
        $account = $user->getAccount();
        $balance = $account->getBalance();

        $calculatedAmount = $this->interestRatePayoutService->calculate($balance, $interestRate);
        $pendingAmount = $this->interestRatePayoutService->getPendingPayoutsAmount($user->getId());
        $totalAmount = new Money(($calculatedAmount->getValue() + $pendingAmount->getValue()), CurrencyType::GBP);

        $this->payoutRepository::removePayoutByUserId($user->getId());

        $this->interestRatePayoutService->deposit($account, $totalAmount);
    }
}

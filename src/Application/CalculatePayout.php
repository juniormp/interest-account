<?php


namespace Chip\InterestAccount\Application;

use Chip\InterestAccount\Application\Command\CalculatePayoutCommand;
use Chip\InterestAccount\Domain\Money\CurrencyType;
use Chip\InterestAccount\Domain\Money\Money;
use Chip\InterestAccount\Domain\Payout\InterestRatePayoutService;
use Chip\InterestAccount\Infrastructure\Repository\Payout\PayoutProvider;
use Chip\InterestAccount\Infrastructure\Repository\User\UserProvider;

class CalculatePayout
{
    private $interestRatePayoutService;
    private $userProvider;
    private $payoutProvider;

    public function __construct(InterestRatePayoutService $interestRatePayoutService, UserProvider $userProvider, PayoutProvider $payoutProvider)
    {
        $this->interestRatePayoutService = $interestRatePayoutService;
        $this->userProvider = $userProvider;
        $this->payoutProvider = $payoutProvider;
    }

    public function execute(CalculatePayoutCommand $calculatePayoutCommand)
    {
        $user = $this->userProvider->findById($calculatePayoutCommand->getId());

        $interestRate = $user->getInterestRateEntity();
        $account = $user->getAccount();
        $balance = $account->getBalance();

        $calculatedAmount = $this->interestRatePayoutService->calculate($balance, $interestRate);
        $pendingAmount = $this->interestRatePayoutService->getPendingPayoutsAmount($user->getId());
        $totalAmount = new Money(($calculatedAmount->getValue() + $pendingAmount->getValue()), CurrencyType::GBP);

        $this->payoutProvider::removePayoutByUserId($user->getId());

        $this->interestRatePayoutService->deposit($account, $totalAmount);
    }
}

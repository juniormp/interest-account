<?php


namespace Chip\InterestAccount\Domain\Payout;

use Chip\InterestAccount\Domain\InterestRate\InterestRate;
use Chip\InterestAccount\Domain\Money\CurrencyType;
use Chip\InterestAccount\Domain\Money\Money;
use Chip\InterestAccount\Infrastructure\Repository\Payout\PayoutRepository;

class InterestRatePayoutService
{
    private $payoutRepository;
    private $payoutFactory;

    public function __construct(PayoutRepository $payoutRepository, PayoutFactory $payoutFactory)
    {
        $this->payoutRepository = $payoutRepository;
        $this->payoutFactory = $payoutFactory;
    }

    public function calculate(Money $userBalance, InterestRate $interestRate): Money
    {
        $rate = $interestRate->convertAnnualRateToThreeDaysRate();
        $totalAmount = $userBalance->getValue() * (1 + $rate);
        $payout = $totalAmount - $userBalance->getValue();

        return new Money($payout, CurrencyType::GBP);
    }

    public function getPendingPayoutsAmount(string $referenceId): Money
    {
        $payouts = $this->payoutRepository->getAllPayoutsByUserId($referenceId);

        $totalAmount = array_reduce($payouts, function ($result, $payout) {
            $result += $payout->getAmount();
            return $result;
        });

        if (is_null($totalAmount)) {
            $totalAmount = 0;
        }

        return new Money($totalAmount, CurrencyType::GBP);
    }
}

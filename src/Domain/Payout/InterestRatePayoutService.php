<?php


namespace Chip\InterestAccount\Domain\Payout;

use Chip\InterestAccount\Domain\Account\Account;
use Chip\InterestAccount\Domain\InterestRate\InterestRate;
use Chip\InterestAccount\Domain\Money\CurrencyType;
use Chip\InterestAccount\Domain\Money\Money;
use Chip\InterestAccount\Domain\Money\MoneyFactory;
use Chip\InterestAccount\Domain\Payout\Exception\NegativeAmountException;
use Chip\InterestAccount\Infrastructure\Repository\Payout\PayoutProvider;

class InterestRatePayoutService
{
    private const ONE_PENNY = 1.0;
    private const MINIMUM_PENNY_TO_DEPOSIT = 0.01;
    private const ZERO_PENNY = 0.0;

    private $payoutProvider;
    private $payoutFactory;
    private $moneyFactory;

    public function __construct(PayoutProvider $payoutProvider, PayoutFactory $payoutFactory, MoneyFactory $moneyFactory)
    {
        $this->payoutProvider = $payoutProvider;
        $this->payoutFactory = $payoutFactory;
        $this->moneyFactory = $moneyFactory;
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
        $payouts = $this->payoutProvider::getAllPayoutsByUserId($referenceId);

        $totalAmount = array_reduce($payouts, function ($result, $payout) {
            $result += $payout->getAmount();
            return $result;
        });

        if (is_null($totalAmount)) {
            $totalAmount = 0;
        }

        return new Money($totalAmount, CurrencyType::GBP);
    }

    public function deposit(Account $account, Money $amount): void
    {
        switch ($amount->getValue()) {
            case ($amount->getValue() >= InterestRatePayoutService::ONE_PENNY):
                $account->deposit($amount);
                break;

            case ($amount->getValue() >= InterestRatePayoutService::MINIMUM_PENNY_TO_DEPOSIT &&
                $amount->getValue() < InterestRatePayoutService::ONE_PENNY):
                $this->insufficientAmountToDeposit($account, $amount);
                break;

            case ($amount->getValue() < InterestRatePayoutService::ZERO_PENNY):
                throw new NegativeAmountException(NegativeAmountException::MESSAGE);
                break;
        }
    }

    private function insufficientAmountToDeposit(Account $account, Money $amount): void
    {
        $payout = $this->payoutFactory->create($account->getReferenceId(), $amount);
        $this->payoutProvider->save($payout);
    }
}

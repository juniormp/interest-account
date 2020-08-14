<?php


namespace Chip\InterestAccount\Domain\Payout;

use Chip\InterestAccount\Domain\Account\Account;
use Chip\InterestAccount\Domain\Money\Money;
use Chip\InterestAccount\Domain\Payout\Exception\NegativeAmountException;
use Chip\InterestAccount\Infrastructure\Repository\Payout\PayoutRepository;

class DepositPayoutService
{
    private const ONE_PENNY = 1.0;
    private const MINIMUM_PENNY_TO_DEPOSIT = 0.01;
    private const ZERO_PENNY = 0.0;

    private $payoutRepository;
    private $payoutFactory;

    public function __construct(PayoutRepository $payoutRepository, PayoutFactory $payoutFactory)
    {
        $this->payoutRepository = $payoutRepository;
        $this->payoutFactory = $payoutFactory;
    }

    public function execute(Account $account, Money $amount): void
    {
        switch ($amount->getValue()) {
            case ($amount->getValue() >= DepositPayoutService::ONE_PENNY):
                $account->deposit($amount);
                break;

            case ($amount->getValue() >= DepositPayoutService::MINIMUM_PENNY_TO_DEPOSIT &&
                $amount->getValue() < DepositPayoutService::ONE_PENNY):
                $this->insufficientAmountToDeposit($account, $amount);
                break;

            case ($amount->getValue() < DepositPayoutService::ZERO_PENNY):
                throw new NegativeAmountException(NegativeAmountException::MESSAGE);
                break;
        }
    }

    private function insufficientAmountToDeposit(Account $account, Money $amount): void
    {
        $payout = $this->payoutFactory->create($account->getReferenceId(), $amount);
        $this->payoutRepository->save($payout);
    }
}

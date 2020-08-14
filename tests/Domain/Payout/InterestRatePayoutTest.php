<?php


use Chip\InterestAccount\Domain\Money\CurrencyType;
use Chip\InterestAccount\Domain\Payout\InterestRatePayoutService;
use Chip\InterestAccount\Domain\Payout\PayoutFactory;
use Chip\InterestAccount\Infrastructure\Repository\Payout\PayoutRepository;
use Chip\InterestAccount\Tests\Support\InterestRateSupportFactory;
use Chip\InterestAccount\Tests\Support\MoneySupportFactory;
use Chip\InterestAccount\Tests\Support\PayoutSupportFactory;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

class InterestRatePayoutTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @covers ::calculate
     */
    public function test_should_calculate_the_payout_given_balance_and_interest_rate()
    {
        $payoutRepository = $this->createMock(PayoutRepository::class);
        $payoutFactory = $this->createMock(PayoutFactory::class);
        $subject = new InterestRatePayoutService($payoutRepository, $payoutFactory);
        $userBalance = MoneySupportFactory::getInstance()::withAmount(5000)::build();
        $interestRate = InterestRateSupportFactory::getInstance()::withRate(1.02)::build();

        $result = $subject->calculate($userBalance, $interestRate);

        $this->assertEquals(0.4228650076320264, $result->getValue());
        $this->assertEquals(CurrencyType::GBP, $result->getCurrencyType());
    }

    /**
     * @covers ::getPendingPayoutsAmount
     */
    public function test_should_return_the_sum_of_all_payouts_amount_from_a_reference_id()
    {
        $payoutRepository = Mockery::mock(PayoutRepository::class);
        $payoutFactory = $this->createMock(PayoutFactory::class);
        $subject = new InterestRatePayoutService($payoutRepository, $payoutFactory);
        $id = "aaa00000-2b32-4964-aaeb-7d3c065bc0f0";
        $amount = MoneySupportFactory::getInstance()::withAmount(0.50)::build();
        $payouts = [
            PayoutSupportFactory::getInstance()::withAmount($amount)::build(),
            PayoutSupportFactory::getInstance()::withAmount($amount)::build()
        ];
        $payoutRepository->shouldReceive('getAllPayoutsByUserId')->with($id)->andReturn($payouts);

        $result = $subject->getPendingPayoutsAmount($id);

        $this->assertEquals(1.0, $result->getValue());
    }
}

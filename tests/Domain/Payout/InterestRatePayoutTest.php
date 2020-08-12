<?php


use Chip\InterestAccount\Domain\Account\Account;
use Chip\InterestAccount\Domain\Money\CurrencyType;
use Chip\InterestAccount\Domain\Money\MoneyFactory;
use Chip\InterestAccount\Domain\Payout\InterestRatePayoutService;
use Chip\InterestAccount\Domain\Payout\PayoutFactory;
use Chip\InterestAccount\Domain\Payout\Exception\NegativeAmountException;
use Chip\InterestAccount\Domain\Payout\Payout;
use Chip\InterestAccount\Infrastructure\Repository\Payout\PayoutProvider;
use Chip\InterestAccount\Tests\Support\InterestRateSupportFactory;
use Chip\InterestAccount\Tests\Support\MoneySupportFactory;
use Chip\InterestAccount\Tests\Support\PayoutSupportFactory;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

class InterestRatePayoutTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function test_should_calculate_the_payout_given_balance_and_interest_rate()
    {
        $payoutProvider = $this->createMock(PayoutProvider::class);
        $payoutFactory = $this->createMock(PayoutFactory::class);
        $moneyFactory = $this->createMock(MoneyFactory::class);
        $subject = new InterestRatePayoutService($payoutProvider, $payoutFactory, $moneyFactory);
        $userBalance = MoneySupportFactory::getInstance()::withAmount(5000)::build();
        $interestRate = InterestRateSupportFactory::getInstance()::withRate(1.02)::build();

        $result = $subject->calculate($userBalance, $interestRate);

        $this->assertEquals(0.4228650076320264, $result->getValue());
        $this->assertEquals(CurrencyType::GBP, $result->getCurrencyType());
    }

    public function test_should_deposit_payout_if_amount_is_equal_to_1_penny()
    {
        $payoutProvider = $this->createMock(PayoutProvider::class);
        $payoutFactory = $this->createMock(PayoutFactory::class);
        $moneyFactory = $this->createMock(MoneyFactory::class);
        $subject = new InterestRatePayoutService($payoutProvider, $payoutFactory, $moneyFactory);
        $account = Mockery::spy(Account::class);
        $amount = MoneySupportFactory::getInstance()::withAmount(1.0)::build();

        $subject->deposit($account, $amount);

        $account->shouldHaveReceived('deposit')->with($amount)->once();
    }

    public function test_should_deposit_payout_if_amount_is_greater_than_1_penny()
    {
        $payoutProvider = $this->createMock(PayoutProvider::class);
        $payoutFactory = $this->createMock(PayoutFactory::class);
        $moneyFactory = $this->createMock(MoneyFactory::class);
        $subject = new InterestRatePayoutService($payoutProvider, $payoutFactory, $moneyFactory);
        $account = Mockery::spy(Account::class);
        $amount = MoneySupportFactory::getInstance()::withAmount(1.1)::build();

        $subject->deposit($account, $amount);

        $account->shouldHaveReceived('deposit')->with($amount)->once();
    }

    public function test_should_save_deposit_payout_if_amount_is_less_than_1_penny()
    {
        $payoutProvider = Mockery::spy(PayoutProvider::class);
        $payoutFactory = $this->createMock(PayoutFactory::class);
        $moneyFactory = $this->createMock(MoneyFactory::class);
        $subject = new InterestRatePayoutService($payoutProvider, $payoutFactory, $moneyFactory);
        $account = $this->createMock(Account::class);
        $payout = $this->createMock(Payout::class);
        $amount = MoneySupportFactory::getInstance()::withAmount(0.99)::build();
        $account->method('getReferenceId')->willReturn('fake-id');
        $payoutFactory->method('create')->with('fake-id', $amount)->willReturn($payout);

        $subject->deposit($account, $amount);

        $payoutProvider->shouldHaveReceived('save')->with($payout)->once();
    }

    public function test_should_do_nothing_if_payout_is_zero()
    {
        $payoutProvider = Mockery::spy(PayoutProvider::class);
        $payoutFactory = Mockery::spy(PayoutFactory::class);
        $moneyFactory = $this->createMock(MoneyFactory::class);
        $subject = new InterestRatePayoutService($payoutProvider, $payoutFactory, $moneyFactory);
        $account = Mockery::spy(Account::class);
        $amount = MoneySupportFactory::getInstance()::withAmount(0)::build();

        $subject->deposit($account, $amount);

        $payoutFactory->shouldNotHaveReceived('create');
        $payoutProvider->shouldNotHaveReceived('save');
    }

    public function test_should_throw_negative_amount_exception_if_amount_is_negative()
    {
        $payoutProvider = Mockery::spy(PayoutProvider::class);
        $payoutFactory = $this->createMock(PayoutFactory::class);
        $moneyFactory = $this->createMock(MoneyFactory::class);
        $subject = new InterestRatePayoutService($payoutProvider, $payoutFactory, $moneyFactory);
        $account = Mockery::spy(Account::class);
        $amount = MoneySupportFactory::getInstance()::withAmount(-0.1)::build();

        $this->expectException(NegativeAmountException::class);
        $this->expectExceptionMessage("AMOUNT CAN NOT BE NEGATIVE");

        $subject->deposit($account, $amount);
    }

    public function test_should_return_the_sum_of_all_payouts_amount_from_a_reference_id()
    {
        $payoutProvider = Mockery::mock(PayoutProvider::class);
        $payoutFactory = $this->createMock(PayoutFactory::class);
        $moneyFactory = $this->createMock(MoneyFactory::class);
        $subject = new InterestRatePayoutService($payoutProvider, $payoutFactory, $moneyFactory);
        $id = "aaa00000-2b32-4964-aaeb-7d3c065bc0f0";
        $amount = MoneySupportFactory::getInstance()::withAmount(0.50)::build();
        $payouts = [
            PayoutSupportFactory::getInstance()::withAmount($amount)::build(),
            PayoutSupportFactory::getInstance()::withAmount($amount)::build()
        ];
        $payoutProvider->shouldReceive('getAllPayoutsByUserId')->with($id)->andReturn($payouts);

        $result = $subject->getPendingPayoutsAmount($id);

        $this->assertEquals(1.0, $result->getValue());
    }
}

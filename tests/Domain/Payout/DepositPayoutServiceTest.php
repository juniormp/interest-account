<?php


use Chip\InterestAccount\Domain\Account\Account;
use Chip\InterestAccount\Domain\Payout\DepositPayoutService;
use Chip\InterestAccount\Domain\Payout\Exception\NegativeAmountException;
use Chip\InterestAccount\Domain\Payout\Payout;
use Chip\InterestAccount\Domain\Payout\PayoutFactory;
use Chip\InterestAccount\Infrastructure\Repository\Payout\PayoutProvider;
use Chip\InterestAccount\Tests\Support\MoneySupportFactory;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

class DepositPayoutServiceTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @@covers  ::execute
     */
    public function test_should_deposit_payout_if_amount_is_equal_to_1_penny()
    {
        $payoutProvider = $this->createMock(PayoutProvider::class);
        $payoutFactory = $this->createMock(PayoutFactory::class);
        $subject = new DepositPayoutService($payoutProvider, $payoutFactory);
        $account = Mockery::spy(Account::class);
        $amount = MoneySupportFactory::getInstance()::withAmount(1.0)::build();

        $subject->execute($account, $amount);

        $account->shouldHaveReceived('deposit')->with($amount)->once();
    }

    /**
     * @@covers  ::execute
     */
    public function test_should_deposit_payout_if_amount_is_greater_than_1_penny()
    {
        $payoutProvider = $this->createMock(PayoutProvider::class);
        $payoutFactory = $this->createMock(PayoutFactory::class);
        $subject = new DepositPayoutService($payoutProvider, $payoutFactory);
        $account = Mockery::spy(Account::class);
        $amount = MoneySupportFactory::getInstance()::withAmount(1.1)::build();

        $subject->execute($account, $amount);

        $account->shouldHaveReceived('deposit')->with($amount)->once();
    }

    /**
     * @@covers  ::execute
     */
    public function test_should_save_deposit_payout_if_amount_is_less_than_1_penny()
    {
        $payoutProvider = Mockery::spy(PayoutProvider::class);
        $payoutFactory = $this->createMock(PayoutFactory::class);
        $subject = new DepositPayoutService($payoutProvider, $payoutFactory);
        $account = $this->createMock(Account::class);
        $payout = $this->createMock(Payout::class);
        $amount = MoneySupportFactory::getInstance()::withAmount(0.99)::build();
        $account->method('getReferenceId')->willReturn('fake-id');
        $payoutFactory->method('create')->with('fake-id', $amount)->willReturn($payout);

        $subject->execute($account, $amount);

        $payoutProvider->shouldHaveReceived('save')->with($payout)->once();
    }

    /**
     * @@covers  ::execute
     */
    public function test_should_do_nothing_if_payout_is_zero()
    {
        $payoutProvider = Mockery::spy(PayoutProvider::class);
        $payoutFactory = Mockery::spy(PayoutFactory::class);
        $subject = new DepositPayoutService($payoutProvider, $payoutFactory);
        $account = Mockery::spy(Account::class);
        $amount = MoneySupportFactory::getInstance()::withAmount(0)::build();

        $subject->execute($account, $amount);

        $payoutFactory->shouldNotHaveReceived('create');
        $payoutProvider->shouldNotHaveReceived('save');
    }

    /**
     * @@covers  ::execute
     */
    public function test_should_throw_negative_amount_exception_if_amount_is_negative()
    {
        $payoutProvider = Mockery::spy(PayoutProvider::class);
        $payoutFactory = $this->createMock(PayoutFactory::class);
        $subject = new DepositPayoutService($payoutProvider, $payoutFactory);
        $account = Mockery::spy(Account::class);
        $amount = MoneySupportFactory::getInstance()::withAmount(-0.1)::build();

        $this->expectException(NegativeAmountException::class);
        $this->expectExceptionMessage("AMOUNT CAN NOT BE NEGATIVE");

        $subject->execute($account, $amount);
    }
}

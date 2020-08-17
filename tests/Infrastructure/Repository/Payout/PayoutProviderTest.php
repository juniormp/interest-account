<?php


use Chip\InterestAccount\Domain\Money\Money;
use Chip\InterestAccount\Infrastructure\Repository\Payout\PayoutProvider;
use Chip\InterestAccount\Tests\Support\MoneySupportFactory;
use Chip\InterestAccount\Tests\Support\PayoutSupportFactory;
use Chip\InterestAccount\Tests\Support\Repository\PayoutSupportRepository;
use PHPUnit\Framework\TestCase;

class PayoutProviderTest extends TestCase
{
    private $subject;

    protected function setUp(): void
    {
        $this->subject = new PayoutProvider();
    }

    protected function tearDown(): void
    {
        PayoutSupportRepository::cleanPayoutData();
    }

    /**
     * @covers ::save
     */
    public function test_should_save_payout()
    {
        $money = MoneySupportFactory::getInstance()::build();
        $payout = PayoutSupportFactory::getInstance()::withAmount($money)::build();

        $this->subject->save($payout);

        $payouts = PayoutSupportRepository::getAll();
        $this->assertCount(1, $payouts);
        $this->assertSame($payout->getReferenceId(), $payouts[0]->getReferenceId());
    }

    /**
     * @covers ::save
     */
    public function test_should_return_saved_payout()
    {
        $money = MoneySupportFactory::getInstance()::build();
        $payout = PayoutSupportFactory::getInstance()::withAmount($money)::build();

        $result = $this->subject->save($payout);

        $this->assertSame($payout, $result);
    }

    /**
     * @covers ::getAllByUserId
     */
    public function test_should_return_all_payouts_by_reference_id()
    {
        $referenceId = "aaa00000-2b32-4964-aaeb-7d3c065bc0f0";
        $amount = MoneySupportFactory::getInstance()::build();
        $payout = PayoutSupportFactory::getInstance()::withReferenceId($referenceId)::withAmount($amount)::build();
        $payout2 = PayoutSupportFactory::getInstance()::withReferenceId("bbb00000-2b32-4964-aaeb-7d3c065bc0f0")
            ::withAmount($amount)::build();
        $payout3 = PayoutSupportFactory::getInstance()::withReferenceId($referenceId)::withAmount($amount)::build();
        PayoutSupportRepository::persistPayout($payout);
        PayoutSupportRepository::persistPayout($payout2);
        PayoutSupportRepository::persistPayout($payout3);

        $result = $this->subject->getAllPayoutsByUserId($referenceId);

        $this->assertCount(2, $result);
    }

    /**
     * @covers ::removeByUserId
     */
    public function test_should_remove_payout_by_user_id()
    {
        $referenceId = "aaa00000-2b32-4964-aaeb-7d3c065bc0f0";
        $amount = MoneySupportFactory::getInstance()::build();
        $payout = PayoutSupportFactory::getInstance()::withReferenceId($referenceId)::withAmount($amount)::build();
        $payout2 = PayoutSupportFactory::getInstance()::withReferenceId("bbb00000-2b32-4964-aaeb-7d3c065bc0f0")
            ::withAmount($amount)::build();
        $payout3 = PayoutSupportFactory::getInstance()::withReferenceId($referenceId)::withAmount($amount)::build();
        PayoutSupportRepository::persistPayout($payout);
        PayoutSupportRepository::persistPayout($payout2);
        PayoutSupportRepository::persistPayout($payout3);

        $this->subject->removePayoutByUserId($referenceId);

        $payouts = PayoutSupportRepository::getAll();

        $this->assertCount(1, $payouts);
        $this->assertEquals($payout2, $payouts[0]);
    }

    /**
     * @covers ::removeByUserId
     */
    public function test_should_reorder_array_index_after_removing_payout()
    {
        $referenceId = "aaa00000-2b32-4964-aaeb-7d3c065bc0f0";
        $amount = $this->createMock(Money::class);
        $payout = PayoutSupportFactory::getInstance()::withReferenceId($referenceId)::withAmount($amount)::build();
        $payout2 = PayoutSupportFactory::getInstance()::withReferenceId("bbb00000-2b32-4964-aaeb-7d3c065bc0f0")
            ::withAmount($amount)::build();
        $payout3 = PayoutSupportFactory::getInstance()::withReferenceId($referenceId)::withAmount($amount)::build();
        PayoutSupportRepository::persistPayout($payout);
        PayoutSupportRepository::persistPayout($payout2);
        PayoutSupportRepository::persistPayout($payout3);

        $key = array_search($payout2, PayoutSupportRepository::getAll());
        $this->assertEquals(1, $key);

        $this->subject->removePayoutByUserId($referenceId);

        $key = array_search($payout2, PayoutSupportRepository::getAll());
        $this->assertEquals(0, $key);
    }
}

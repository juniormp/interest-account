<?php


use Chip\InterestAccount\Domain\Money\Money;
use Chip\InterestAccount\Domain\Payout\Payout;
use Chip\InterestAccount\Infrastructure\Repository\Payout\PayoutProvider;
use PHPUnit\Framework\TestCase;

class PayoutProviderTest extends TestCase
{
    private $subject;

    protected function setUp(): void
    {
        $this->subject = PayoutProvider::getInstance();
    }

    protected function tearDown(): void
    {
        $this->subject->destroy();
    }

    public function test_should_return_the_same_provider_instance()
    {
        $firstUserProvider = PayoutProvider::getInstance();
        $secondUserProvider = PayoutProvider::getInstance();

        $this->assertSame($firstUserProvider, $secondUserProvider);
    }

    public function test_should_save_payout()
    {
        $this->subject->cleanPayouts();
        $payout = $this->createMock(Payout::class);

        $this->subject->save($payout);

        $this->assertCount(1, $this->subject::getAll());
        $this->assertSame($payout, $this->subject::getAll()[0]);
    }

    public function test_should_return_saved_payout()
    {
        $payout = $this->createMock(Payout::class);

        $result = $this->subject->save($payout);

        $this->assertSame($payout, $result);
    }

    public function test_should_return_all_payouts_by_reference_id()
    {
        $id = "aaa00000-2b32-4964-aaeb-7d3c065bc0f0";
        $money = $this->createMock(Money::class);
        $payout = new Payout($id, $money);
        $payout2 = new Payout("bbb00000-2b32-4964-aaeb-7d3c065bc0f0", $money);
        $payout3 = new Payout($id, $money);
        $this->subject::save($payout);
        $this->subject::save($payout2);
        $this->subject::save($payout3);

        $result = $this->subject->getAllPayoutsByUserId($id);

        $this->assertCount(2, $result);
    }

    public function test_should_remove_payout_by_user_id()
    {
        $this->subject->cleanPayouts();
        $id = "aaa00000-2b32-4964-aaeb-7d3c065bc0f0";
        $money = $this->createMock(Money::class);
        $payout = new Payout($id, $money);
        $payout2 = new Payout("bbb00000-2b32-4964-aaeb-7d3c065bc0f0", $money);
        $payout3 = new Payout($id, $money);
        $this->subject::save($payout);
        $this->subject::save($payout2);
        $this->subject::save($payout3);

        $this->subject->removePayoutByUserId($id);
        $this->assertCount(1, $this->subject::getAll());
        $this->assertEquals($payout2, $this->subject::getAll()[0]);
    }

    public function test_should_reorder_array_index_after_removing_payout()
    {
        $this->subject->cleanPayouts();
        $id = "aaa00000-2b32-4964-aaeb-7d3c065bc0f0";
        $money = $this->createMock(Money::class);
        $payout = new Payout($id, $money);
        $payout2 = new Payout("bbb00000-2b32-4964-aaeb-7d3c065bc0f0", $money);
        $payout3 = new Payout($id, $money);
        $this->subject::save($payout);
        $this->subject::save($payout2);
        $this->subject::save($payout3);

        $key = array_search($payout2, $this->subject::getAll());
        $this->assertEquals(1, $key);

        $this->subject->removePayoutByUserId($id);

        $key = array_search($payout2, $this->subject::getAll());
        $this->assertEquals(0, $key);
    }
}

<?php


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
}

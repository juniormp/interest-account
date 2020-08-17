<?php


use Chip\InterestAccount\Infrastructure\Scheduler\PayoutScheduler;
use PHPUnit\Framework\TestCase;

class PayoutSchedulerTest extends TestCase
{
    private $container;
    private $subject;

    protected function setUp(): void
    {
        $this->container = require '/Users/mauricio.junior/workspace/interest-account/app/bootstrap.php';
        $this->subject = $this->container->get(PayoutScheduler::class);
    }

    /**
     * @covers ::execute
     */
    public function test_should_execute_schedule_to_close_payouts()
    {

    }
}

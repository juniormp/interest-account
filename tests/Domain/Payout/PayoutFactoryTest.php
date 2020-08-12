<?php


use Chip\InterestAccount\Domain\Money\Money;
use Chip\InterestAccount\Domain\Payout\PayoutFactory;
use PHPUnit\Framework\TestCase;

class PayoutFactoryTest extends TestCase
{
    public function test_should_return_payout_with_the_correct_data()
    {
        $subject = new PayoutFactory();
        $referenceId = "aaa00000-2b32-4964-aaeb-7d3c065bc0f0";
        $amount = $this->createMock(Money::class);

        $result = $subject->create($referenceId, $amount);

        $this->assertEquals($referenceId, $result->getReferenceId());
        $this->assertEquals($amount, $result->getMoney());
    }
}

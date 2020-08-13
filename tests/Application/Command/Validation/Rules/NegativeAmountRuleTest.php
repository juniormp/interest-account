<?php


use Chip\InterestAccount\Application\Command\Validation\Rules\NegativeAmountRule;
use Chip\InterestAccount\Application\Command\Validation\ValidationError;
use PHPUnit\Framework\TestCase;

class NegativeAmountRuleTest extends TestCase
{
    /**
     * @covers ::validate
     */
    public function test_should_validate_positive_amount()
    {
        $positiveAmount = 200.0;

        NegativeAmountRule::validate($positiveAmount);

        // TODO How to assert that an exceptions was not thrown ?
        $success = true;
        $this->assertTrue($success);
    }

    /**
     * @covers ::validate
     */
    public function test_should_throw_exception_for_negative_amount()
    {
        $negativeAmount = -200.0;

        $this->expectException(ValidationError::class);
        $this->expectExceptionMessage("AMOUNT CAN NOT BE NEGATIVE");

        NegativeAmountRule::validate($negativeAmount);
    }
}
